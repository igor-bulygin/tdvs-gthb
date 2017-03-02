<?php
namespace app\models;

use app\helpers\Utils;

/**
 * @property string $name
 * @property string $last_name
 * @property string $brand_name
 * @property string $country
 * @property string $city
 * @property \MongoDate $bday
 * @property string $surnames
 *
 * @method Person getParentObject()
 */
class PersonPersonalInfo extends EmbedModel
{

	public function attributes()
	{
		return [
			'name',
			'last_name',
			'brand_name',
			'country',
			'city',
			'bday',
			'surnames',
		];
	}

	public function getParentAttribute()
	{
		return "personal_info";
	}

	/**
	 * Get the city from Person.
	 * First get city, otherwise get country
	 *
	 * @return string|null
	 */
	public function getCityLabel()
	{
		if (isset($this->city)) {
			return $this->city;
		} elseif (isset($this->country)) {
			/** @var Country $country */
			$country = Country::findOne(['country_code' => $this->country]);
			return Utils::l($country->country_name);
		}

		return null;
	}

	/**
	 * Get brand name from Person
	 *
	 * @return string
	 */
	public function getBrandName()
	{
		return (isset($this->brand_name)) ? $this->brand_name : $this->name . (!empty($this->last_name) ? $this->last_name : '');
	}

	/**
	 * Get the location from Person (city and country).
	 *
	 * @return string
	 */
	public function getLocationLabel()
	{
		$location = [];

		if (!empty($this->city)) {
			$location[] = $this->city;
		}
		/** @var Country $country */
		if (!empty($this->country)) {
			$country = Country::findOne(['country_code' => $this->country]);
			if ($country) {
				$location[] = Utils::l($country->country_name);
			}
		}

		return implode(", ", $location);
	}

	public function load($data, $formName = null)
	{
		// little hack, while don't rename database attributes
		if (array_key_exists("first_name", $data)) {
			$this->name = $data["first_name"];
		}

		$loaded = parent::load($data, $formName);

		if (empty($this->brand_name)) {
			$this->brand_name = rtrim($this->name . ' ' . $this->last_name);
		}

		return $loaded;
	}

	public function rules()
	{
		return [
			[
				$this->attributes(),
				'safe',
				'on' => [
					Person::SCENARIO_DEVISER_CREATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
					Person::SCENARIO_INFLUENCER_CREATE_DRAFT,
				]
			],
			[['name', 'brand_name', 'country', 'city'], 'required', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
		];
	}

	/**
	 * Add additional error to make easy show labels in client side
	 */
	public function afterValidate()
	{
		parent::afterValidate();
		foreach ($this->errors as $attribute => $error) {
			switch ($attribute) {
				case 'brand_name':
				case 'name':
				case 'country':
				case 'city':
					$this->getParentObject()->addError("required", $attribute);
					break;
			}
		};
	}
}