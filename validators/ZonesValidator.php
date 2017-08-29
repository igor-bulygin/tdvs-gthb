<?php
namespace app\validators;

use app\models\Country;
use yii\validators\Validator;

class ZonesValidator extends Validator
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
		$zones = $object->{$attribute};

		foreach ($zones as $zone) {

			$parts = explode('/', $zone);
			if (empty($parts)) {
				$this->addError($object, $attribute, sprintf('Code %s is not valid. Each zone must be in format WW/{continent_code}/{country_code}', $zone));
				break;
			}
			$worldWide = $parts[0];
			if ($worldWide != Country::WORLD_WIDE) {
				$this->addError($object, $attribute, sprintf('Code %s is not valid. First part of each zone must be Worldwide (WW)', $worldWide));
				break;
			}

			if (count($parts) > 1) {
				$continentCode = $parts[1];
				if (count($parts) > 2) {
					$countryCode = $parts[2];
					$country = Country::findOne(['country_code' => $countryCode]);
					if (empty($country)) {
						$this->addError($object, $attribute, sprintf('Country code %s is not valid', $countryCode));
					} elseif ($country->continent != $continentCode) {
						$this->addError($object, $attribute, sprintf('Country code %s is not valid for continent %s', $countryCode, $continentCode));
					}
				} elseif (!Country::validateContinentCode($continentCode)) {
					$this->addError($object, $attribute, sprintf('Continent code %s is not valid', $continentCode));
				}
			}
		}
	}
}