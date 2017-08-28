<?php
namespace app\validators;

use app\models\Returns;
use yii\validators\Validator;

class ReturnsValidator extends Validator
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
		$returns = $object->{$attribute};
		if (empty($returns)) {
			return;
		}
		if (!isset($returns['type'])) {
			$this->addError($object, $attribute, 'Returns value must have a type property');

			return;
		}

		$availableValues = Returns::getAvailableValues();
		if (!isset($availableValues[$returns['type']])) {
			$this->addError($object, $attribute, sprintf('Value %s not valid for returns type', $returns['type']));

			return;
		}

		if ($returns['type'] == Returns::NONE) {
			if (!empty($returns['value'])) {
				$this->addError($object, $attribute, 'Warranty value must be empty for type of warranty '.Returns::NONE);
			}
			return;
		}

		if (!isset($returns['value'])) {
			$this->addError($object, $attribute, 'Returns value must have a value property');

			return;
		}

		if (!is_int($returns['value']) || $returns['value'] < 0) {
			$this->addError($object, $attribute,
				sprintf('Value %s not valid for returns value. Must be an integer >= 0', $returns['value']));

			return;
		}
	}
}