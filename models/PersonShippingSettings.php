<?php
namespace app\models;

/**
 * @property string weight_measure
 * @property string currency
 * @property int shipping_time
 * @property int shipping_express_time
 * @property array zones
 * @property array prices
 *
 * @method Person getParentObject()
 */
class PersonShippingSettings extends EmbedModel
{

	public function attributes()
	{
		return [
			'weight_measure',
			'currency',
			'shipping_time',
			'shipping_express_time',
			'zones',
			'prices',
		];
	}

	public function getParentAttribute()
	{
		return "shipping_settings";
	}

	public function rules()
	{
		return [
			[
				$this->attributes(),
				'safe',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			[['weight_measure', 'currency', 'shipping_time'], 'required'],
			[
				'weight_measure',
				'in',
				'range' => MetricType::UNITS[MetricType::WEIGHT],
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			[
				'currency',
				'in',
				'range' => Currency::getCurrenciesCodes(),
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			[
				['shipping_time', 'shipping_express_time'],
				'integer', 'min' => 1,
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			[
				'zones',
				'validateZones',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			[
				'prices',
				'validatePrices',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
		];
	}

	public function validateZones($attribute, $params)
	{
		$zones = $this->zones;

		foreach ($zones as $zone) {

			$parts = explode('/', $zone);
			if (empty($parts)) {
				$this->addError('zones', sprintf('Code %s is not valid. Each zone must be in format WW/{continent_code}/{country_code}', $zone));
				break;
			}
			$worldWide = $parts[0];
			if ($worldWide != Country::WORLD_WIDE) {
				$this->addError('zones', sprintf('Code %s is not valid. First part of each zone must be Worldwide (WW)', $worldWide));
				break;
			}

			if (count($parts) > 1) {
				$continentCode = $parts[1];
				if (count($parts) > 2) {
					$countryCode = $parts[2];
					$country = Country::findOne(['country_code' => $countryCode]);
					if (empty($country)) {
						$this->addError('zones', sprintf('Country code %s is not valid', $countryCode));
					} elseif ($country->continent != $continentCode) {
						$this->addError('zones', sprintf('Country code %s is not valid for continent %s', $countryCode, $continentCode));
					}
				} elseif (!Country::validateContinentCode($continentCode)) {
					$this->addError('zones', sprintf('Continent code %s is not valid', $continentCode));
				}
			}
		}
	}

	public function validatePrices($attribute, $params)
	{
		$positiveFields = [
			'min_weight',
			'price',
			'price_express',
		];

		$prices = $this->prices;

		foreach ($prices as $price) {

			foreach ($positiveFields as $field) {
				$value = $price[$field];
				if (empty($value) || !is_numeric($value) || $value <= 0) {
					$this->addError('prices', sprintf('%s must be a positive value', $field));
				}
			}
			$maxWeight = $price['max_weight'];
			if ($maxWeight !== null && (!is_numeric($maxWeight) || $maxWeight <= 0)) {
				$this->addError('prices', 'max_weight must be a positive value or null');
			}
		}
	}
}