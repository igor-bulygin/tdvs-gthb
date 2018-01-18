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

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $serializeFields = [];

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $retrieveExtraFields = [];

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
			'shipping_time',
			'shipping_express_time',
			'free_shipping_from',
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
				'app\validators\CountryCodeValidator',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			[
				['shipping_time', 'shipping_express_time', 'free_shipping_from'],
				'integer',
				'min' => 1,
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

	public function validatePrices($attribute, $params)
	{
		$prices = $this->{$attribute};

		$lastWeight = 0;
		foreach ($prices as $price) {

			$hasErrors = false;
			if (!array_key_exists('min_weight', $price)) {
				$this->addError('prices', 'min_weight is required');
				$hasErrors = true;
			}

			if (!array_key_exists('max_weight', $price)) {
				$this->addError('prices', 'max_weight is required');
				$hasErrors = true;
			}

			if (!array_key_exists('price', $price)) {
				$this->addError('prices', 'price is required');
				$hasErrors = true;
			}

			if ($this->shipping_express_time && !array_key_exists('price_express', $price)) {
				$this->addError('prices', 'price_express is required');
				$hasErrors = true;
			}

			if ($hasErrors) {
				continue;
			}

			$minWeight = $price['min_weight'];
			$maxWeight = $price['max_weight'];
			$price = $price['price'];

			if (!is_numeric($minWeight) || $minWeight < $lastWeight) {
				$this->addError('prices', sprintf('min_weight %s must be a positive value greater or equal to %s', $minWeight, $lastWeight));
			}

			if ($maxWeight !== null && (!is_numeric($maxWeight) || $maxWeight <= $minWeight)) {
				$this->addError('prices', sprintf('max_weight %s must be null, or positive value greater than min_weight %s', $maxWeight, $minWeight));
			}

			if (!is_numeric($price) || $price < 0) {
				$this->addError('prices', sprintf('price %s must be a positive value or zero', $price));
			}

			if (isset($price['price_express'])) {
				$priceExpress = $price['price_express'];
				if (!is_numeric($priceExpress) || $priceExpress < 0) {
					$this->addError('prices', sprintf('price_express %s must be a positive value or zero', $priceExpress));
				}
			}

			$lastWeight = $maxWeight;
		}
	}


	public function getShippingSettingRange($weight)
	{
		foreach ($this->prices as $price) {
			if ($price['min_weight'] <= $weight && ($price['max_weight'] == null || $price['max_weight'] >= $weight)) {
				return $price;
			}
		}

		return null;
	}
}