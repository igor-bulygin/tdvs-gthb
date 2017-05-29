<?php
namespace app\models;

/**
 * @property string country_code
 * @property int shipping_time
 * @property int shipping_express_time
 * @property array prices
 * @property array observations
 *
 * @method Person getParentObject()
 */
class PersonShippingSettings extends EmbedModel
{
	//TODO deprecate:
	public $zones;
	//TODO deprecate:
	public $weight_measure;
	//TODO deprecate:
	public $currency;

	public function attributes()
	{
		return [
			'country_code',
			/*
			'weight_measure',
			'currency',
			*/
			'shipping_time',
			'shipping_express_time',
			'prices',
			'observations',
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
			[['country_code', 'shipping_time'], 'required'],
			[
				'country_code',
				'in',
				'range' => Country::getCountryCodes(),
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			/*
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
			*/
			[
				['shipping_time', 'shipping_express_time'],
				'integer', 'min' => 1,
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			/*
			[
				'zones',
				'validateZones',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			*/
			[
				'prices',
				'validatePrices',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			[
				'observations',
				'app\validators\TranslatableValidator',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
		];
	}

	/*
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
	*/

	public function validatePrices($attribute, $params)
	{
		$prices = $this->prices;

		$last = 0;
		foreach ($prices as $price) {

			if (!array_key_exists('min_weight', $price)) {
				$this->addError('prices', 'min_weight is required');
			}

			if (!array_key_exists('max_weight', $price)) {
				$this->addError('prices', 'max_weight is required');
			}

			if (!array_key_exists('price', $price)) {
				$this->addError('prices', 'price is required');
			}

			if ($this->shipping_express_time && !array_key_exists('price_express', $price)) {
				$this->addError('prices', 'price_express is required');
			}

			$minWeight = $price['min_weight'];
			if ($minWeight !== null) {
				if (!is_numeric($minWeight) || $minWeight < $last) {
					$this->addError('prices', sprintf('min_weight %s must be a positive value greater or equal to %s', $minWeight, $last));
				}
			}

			$maxWeight = $price['max_weight'];
			if ($maxWeight !== null) {
				if (!is_numeric($maxWeight) || $maxWeight <= $minWeight) {
					$this->addError('prices', sprintf('max_weight %s must be greater than min_weight %s', $maxWeight, $minWeight));
				}
			}

			$price = $price['price'];
			if ($price !== null) {
				if (!is_numeric($price) || $price < 0) {
					$this->addError('prices', sprintf('price %s must be a positive value or zero', $price));
				}
			}

			if (isset($price['price_express'])) {
				$priceExpress = $price['price_express'];
				if (!is_numeric($priceExpress) || $priceExpress < 0) {
					$this->addError('prices', sprintf('price_express %s must be a positive value or zero', $priceExpress));
				}
			}

			$last = $maxWeight;
		}
	}
}