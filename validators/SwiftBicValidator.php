<?php

namespace app\validators;

use app\helpers\CActiveRecord;
use yii\validators\RequiredValidator;
use yii\validators\Validator;


class SwiftBicValidator extends Validator
{

	public static $regExp = "/^[A-Z]{6}[A-Z0-9]{2}([A-Z0-9]{3})?$/";

	/**
	 * @var boolean whether the attribute value can be null or empty. Defaults to true,
	 * meaning that if the attribute is empty, it is considered valid.
	 */
	public $allowEmpty = true;

	/**
	 * Validates a single attribute.
	 *
	 * @param CActiveRecord $object the data object being validated
	 * @param string $attribute the name of the attribute to be validated.
	 */
	public function validateAttribute($object, $attribute)
	{
		$value = $object->$attribute;
		if ($this->isEmpty($value)) {
			if ($this->allowEmpty) {
				return;
			} else {
				$arrValidators = $object->getValidators();
				foreach ($arrValidators as $objValidator) { // do not duplicate error message if attribute is already required
					if ($objValidator instanceof RequiredValidator)
						return;
				}

				$message = $this->message !== null ? $this->message : sprintf('%s cannot be blank', $attribute);

				$this->addError($object, $attribute, $message);
			}
		} else {
			$return = $this->validateSwiftBic($value);

			if (!$return) {
				$message = $this->message !== null ? $this->message : sprintf('%s is not a valid swift/bic number', $value);
				$this->addError($object, $attribute, $message);
			}
		}
	}

	/**
	 * Validates swift/bic number
	 *
	 * @param string $value
	 *
	 * @return bool TRUE if validated
	 */
	public function validateSwiftBic($value)
	{
		preg_match(self::$regExp, $value, $matches);
		return !empty($matches);
	}
}