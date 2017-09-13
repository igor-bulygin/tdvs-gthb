<?php
namespace app\validators;

use yii\validators\Validator;

class SpacesFilterValidator extends Validator
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
	public function validateAttribute($model, $attribute)
	{
		$value = $model->$attribute;
		if (is_string($value)) {
			$model->$attribute = $this->removeSpaces($value);
		} elseif (is_array($value)) {
			$values = $value;
			foreach ($values as $k => $v) {
				$values[$k] = $this->removeSpaces($v);
			}
			$model->$attribute = $values;
		}
	}

	/**
	 * Removes multiple spaces of an input string
	 * For example:
	 * 		input = 'This  is  a    test   '
	 * 		output = 'This is a test'
	 *
	 * @param string $input
	 *
	 * @return string
	 */
	protected function removeSpaces($input)
	{
		return preg_replace('!\s+!', ' ', $input);
	}
}