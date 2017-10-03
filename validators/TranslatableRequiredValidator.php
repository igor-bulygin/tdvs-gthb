<?php
namespace app\validators;

use app\models\Lang;
use yii\validators\Validator;

class TranslatableRequiredValidator extends Validator
{
	public $scenario;
	public $model;

	public function validate($values, &$error = null)
	{
		if (!is_array($values)) {
			$error = 'Must be an array';

			return false;
		}

		if (count($values) == 0) {
			$error = 'Can not be empty';

			return false;
		}

		$requiredLangs = Lang::getRequiredLanguages();
		foreach ($requiredLangs as $langCode => $langDesc) {
			if (!isset($values[$langCode]) || empty($values[$langCode])) {
				$error = sprintf('%s translation is required', $langDesc);

				return false;
			}

			if (!is_string($values[$langCode])) {
				$error = sprintf('%s translation must be a string', $langDesc);

				return false;
			}
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
		$values = $object->{$attribute};

		if (!is_array($values)) {
			$this->addError($object, $attribute, 'Must be a a multi language field');
		} else {
			if (count($values) == 0) {
				$this->addError($object, $attribute, 'Can not be empty');
			}

			$requiredLangs = Lang::getRequiredLanguages();
			foreach ($requiredLangs as $langCode => $langDesc) {
				if (!isset($values[$langCode]) || empty($values[$langCode])) {
					$this->addError($object, $attribute,
						sprintf('%s translation is required for %s', $langDesc, $attribute));
				} elseif (!is_string($values[$langCode])) {
					$this->addError($object, $attribute,
						sprintf('%s translation must be a string for %s', $langDesc, $attribute));

				}
			}
		}
	}
}