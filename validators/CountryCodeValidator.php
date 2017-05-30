<?php
namespace app\validators;

use app\models\Country;
use yii\validators\Validator;

class CountryCodeValidator extends Validator
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
		$countryCode = $object->{$attribute};
		if (!in_array($countryCode, Country::getCountryCodes())) {
			$this->addError($object, $attribute, sprintf('Country code %s is not valid', $countryCode));
		}
	}
}