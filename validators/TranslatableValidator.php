<?php
namespace app\validators;

use app\models\Lang;
use yii\validators\Validator;

class TranslatableValidator extends Validator
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

		foreach ($values as $key => $item) {
			if (!array_key_exists($key, Lang::getValidLanguages())) {
				$error = sprintf('Language "%s" is not valid', $key);
				return false;
			}

			if (!empty($item) && !is_string($item)) {
				$error = sprintf('Language "%s" translation must be a string', $key);
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

			foreach ($values as $key => $item) {
				$languageName = Lang::getLanguageName($key) ?: $key;
				if (!array_key_exists($key, Lang::getValidLanguages())) {
					$this->addError($object, $attribute,
						sprintf('Language %s not valid for %s', $languageName, $attribute));
				} elseif (!empty($item) && !is_string($item)) {
					$this->addError($object, $attribute,
						sprintf('%s translation must be a string for %s', $languageName, $attribute));
				}
			}
		}
	}
}