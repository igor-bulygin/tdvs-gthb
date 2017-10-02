<?php
namespace app\validators;

use app\models\Product;
use app\models\Tag;
use yii\validators\Validator;

class PriceStockValidator extends Validator
{
	public $scenario;
	public $model;

	/**
	 * Validates a single attribute.
	 * Child classes must implement this method to provide the actual validation logic.
	 *
	 * @param \yii\mongodb\ActiveRecord $object the data object to be validated
	 * @param string $attribute the name of the attribute to be validated.
	 */
	public function validateAttribute($object, $attribute)
	{
		$positiveFields = ['price', 'weight', 'width', 'height', 'length'];

		$priceStock = $object->{$attribute};
		if (!is_array($priceStock)) {
			$this->addError($object, $attribute, 'Pricestock must be an array');
		} else {

			foreach ($priceStock as $item) {
				if (!isset($item['original_artwork']) || !$item['original_artwork']) {

					if (!isset($item['options'])) {
						$this->addError($object, $attribute, 'Pricestock options must be an array');
						continue;
					}

					if ($object->scenario == Product::SCENARIO_PRODUCT_PUBLIC && empty($item['options'])) {
						$this->addError($object, $attribute, 'Pricestock options must be no empty');
					}

					foreach ($item['options'] as $optionId => $values) {
						$optionId = (string)$optionId; // force cast to string (short_id are allways strings)
						$tag = Tag::findOne(["short_id" => $optionId]);
						if (!$tag || $tag->isRareTag()) {
							// TODO: wtf do here?
							continue;
						}
						/* @var $tag Tag */
						if (!$tag) {
							$this->addError($object, $attribute, sprintf('Option %s not found', $optionId));
						} else {
							foreach ($values as $value) {
								if (is_array($value)) {
									foreach ($value as $oneValue) {
										$optionTag = $tag->getOptionTagByValue($oneValue);
										if (!$optionTag) {
											$this->addError($object, $attribute,
												sprintf('Value %s not valid for tag %s', $oneValue, $optionId));
										}
									}
								} else {
									$optionTag = $tag->getOptionTagByValue($value);
									if (!$optionTag) {
										$this->addError($object, $attribute,
											sprintf('Value %s not valid for tag %s', $value, $optionId));
									}
								}
							}
						}
					}
				}

				// Validatios when an item is available and we are publishing the product
				if ($item['available'] && $object->scenario == Product::SCENARIO_PRODUCT_PUBLIC) {

					// Validate positive fields
					foreach ($positiveFields as $field) {
						if (!isset($item[$field]) || !is_numeric($item[$field]) || $item[$field] < 0) {
							$this->addError($object, $attribute, sprintf('%s must be a positive value', $field));
						}
					}

					// validate stock field (can be null)
					if (!array_key_exists('stock', $item)) {
						$this->addError($object, $attribute, 'Stock is required');
					} else {
						$value = $item['stock'];
						if ($value !== null) {
							if (!is_numeric($value) || !is_int($value) || $value < 0) {
								$this->addError($object, $attribute, 'Stock must be a positive integer value');
							}
						}
					}
				}
			}
		}
	}
}