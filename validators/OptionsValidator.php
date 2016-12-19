<?php
namespace app\validators;

use app\models\Lang;
use app\models\Product2;
use app\models\Tag;
use Yii;
use yii\validators\Validator;

class OptionsValidator extends Validator
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
		$options = $object->{$attribute};
//		foreach ($options as $optionId => $values) {
//			if ($optionId == 20000) {
//				//wtf do here??
//				continue;
//			}
//			$tag = Tag::findOne(["short_id" => $optionId]); /* @var $tag Tag */
//			if (!$tag) {
//				$this->addError($object, $attribute, sprintf('Option %s not found', $optionId));
//			} else {
//				foreach ($values as $value) {
//					if (empty($value)) {
//						$this->addError($object, $attribute, sprintf('Option %s must have a selected value', $tag->name[Lang::EN_US]));
//					} elseif (is_array($value)) {
//						foreach ($value as $oneValue) {
//							$optionTag = $tag->getOptionTagByValue($oneValue);
//							if (!$optionTag) {
//								$this->addError($object, $attribute, sprintf('Value %s not valid for tag %s', $oneValue, $tag->name[Lang::EN_US]));
//							}
//						}
//					} else {
//						$optionTag = $tag->getOptionTagByValue($value);
//						if (!$optionTag) {
//							$this->addError($object, $attribute, sprintf('Value %s not valid for tag %s', $value, $tag->name[Lang::EN_US]));
//						}
//					}
//				}
//			}
//		}
		if (isset($object->categories) && is_array($object->categories)) {
			$categories = $object->categories;
			foreach ($categories as $categoryId) {
				$tags = Tag::findAll(['categories' => $categoryId]);
				foreach ($tags as $tag) {
					if (isset($options[$tag->short_id])) {
						$values = $options[$tag->short_id];
						foreach ($values as $value) {
							if ($tag->required && $object->scenario == Product2::SCENARIO_PRODUCT_PUBLIC && empty($value)) {
								$this->addError($object, $attribute, sprintf('Option %s must have a selected value', $tag->name[Lang::EN_US]));
							} elseif (is_array($value)) {
								foreach ($value as $oneValue) {
									$optionTag = $tag->getOptionTagByValue($oneValue);
									if (!$optionTag) {
										$this->addError($object, $attribute, sprintf('Value %s not valid for tag %s', $oneValue, $tag->name[Lang::EN_US]));
									}
								}
							} else {
								$optionTag = $tag->getOptionTagByValue($value);
								if (!$optionTag) {
									$this->addError($object, $attribute, sprintf('Value %s not valid for tag %s', $value, $tag->name[Lang::EN_US]));
								}
							}
						}
					}
				}
			}
		}
	}
}