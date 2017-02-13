<?php
namespace app\validators;

use app\models\Product2;
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
		$positiveFields = ['price', 'stock', 'weight', 'width', 'height', 'length'];
//		$positiveFields = ['price', 'stock', 'weight'];
		$priceStock = $object->{$attribute};
		if (!is_array($priceStock)) {
			$this->addError($object, $attribute, 'Pricestock must be an array');
		} else {
			$noOptionIdOptions = ['size', 'type']; // this values can be received and must be considered valid, although there are not options ids
			foreach ($priceStock as $item) {
				foreach ($item['options'] as $optionId => $values) {
					$optionId = (string) $optionId; // force cast to string (short_id are allways strings)
					if (in_array($optionId, $noOptionIdOptions)) {
						// TODO: nothing to do here??
						continue;
					}
					$tag = Tag::findOne(["short_id" => $optionId]);
					/* @var $tag Tag */
					if (!$tag) {
						$this->addError($object, $attribute, sprintf('Option %s not found', $optionId));
					} else {
						foreach ($values as $value) {
							if (is_array($value)) {
								foreach ($value as $oneValue) {
									$optionTag = $tag->getOptionTagByValue($oneValue);
									if (!$optionTag) {
										$this->addError($object, $attribute, sprintf('Value %s not valid for tag %s', $oneValue, $optionId));
									}
								}
							} else {
								$optionTag = $tag->getOptionTagByValue($value);
								if (!$optionTag) {
									$this->addError($object, $attribute, sprintf('Value %s not valid for tag %s', $value, $optionId));
								}
							}
						}
					}
				}
				if ($item['available'] && $object->scenario == Product2::SCENARIO_PRODUCT_PUBLIC) {
					foreach ($positiveFields as $field) {
						if (!isset($item[$field]) || !is_numeric($item[$field]) || $item[$field] <= 0) {
							$this->addError($object, $attribute, sprintf('%s must be a positive value', $field));
						}
					}
				}
			}
		}
	}
}