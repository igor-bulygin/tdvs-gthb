<?php
namespace app\validators;

use app\models\Warranty;
use yii\validators\Validator;

class WarrantyValidator extends Validator
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
		$warranty = $object->{$attribute};
		if (empty($warranty)) {
			return;
		}
		if (!isset($warranty['type'])) {
			$this->addError($object, $attribute, 'Warranty value must have a type property');

			return;
		}

		$availableValues = Warranty::getAvailableValues();
		if (!isset($availableValues[$warranty['type']])) {
			$this->addError($object, $attribute, sprintf('Value %s not valid for warranty type', $warranty['type']));

			return;
		}

		if ($warranty['type'] == Warranty::NONE) {
			if (!empty($warranty['value'])) {
				$this->addError($object, $attribute, 'Warranty value must be empty for type of warranty '.Warranty::NONE);
			}

			return;
		}

		if (!isset($warranty['value'])) {
			$this->addError($object, $attribute, 'Warranty value must have a value property');

			return;
		}

		if (!is_int($warranty['value']) || $warranty['value'] < 0) {
			$this->addError($object, $attribute,
				sprintf('Value %s not valid for warranty value. Must be an integer >= 0', $warranty['value']));

			return;
		}
	}
}