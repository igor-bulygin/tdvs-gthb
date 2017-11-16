<?php
namespace app\validators;

use app\models\Category;
use yii\validators\Validator;

class CategoryIdValidator extends Validator
{
	public $scenario;
	public $model;

	public function validate($value, &$error = null)
	{
		$category_id = $value;
		$category = Category::findOneSerialized($category_id);
		if (!$category) {
			$error = sprintf('Category %s not found', $category_id);
			return false;
		}

		return true;
	}

	/**
	 * Validates a single attribute.
	 * Child classes must implement this method to provide the actual validation logic.
	 *
	 * @param \yii\mongodb\ActiveRecord $object the data object to be validated
	 * @param string $attribute the name of the attribute to be validated.
	 */
	public function validateAttribute($object, $attribute)
	{
		$category_id = $object->{$attribute};
		$category = Category::findOneSerialized($category_id);
		if (!$category) {
			$this->addError($object, $attribute, sprintf('Category %s not found', $category_id));
		}
	}
}