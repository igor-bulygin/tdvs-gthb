<?php
namespace app\models;

use app\helpers\Utils;

/**
 * @property string $name
 * @property string $last_name
 * @property string $brand_name
 * @property string $vat_id
 * @property string $country
 * @property string $city
 * @property string $address
 * @property string $phone_number_prefix
 * @property string $phone_number
 * @property string $zip
 *
 * @property \MongoDate $bday
 * @property string $surnames
 *
 * @method Person getParentObject()
 */
class PersonPersonalInfo extends EmbedModel
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

	// TODO deprecate
	public $bday;
	// TODO deprecate
	public $surnames;
	// TODO deprecate
	public $street;
	// TODO deprecate
	public $number;

	public function attributes()
	{
		return [
			'name',
			'last_name',
			'brand_name',
			'vat_id',
			'country',
			'city',
			'address',
			'phone_number_prefix',
			'phone_number',
			'zip',
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
	 * Get the visible name of the person
	 *
	 * @return string
	 */
	public function getVisibleName()
	{
		return $this->brand_name ?: trim($this->name . ' '.$this->last_name);
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

//		if (empty($this->brand_name)) {
//			$this->brand_name = rtrim($this->name . ' ' . $this->last_name);
//		} elseif (empty($this->name)) {
//			$this->name = $this->brand_name;
//		}

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
					Person::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					Person::SCENARIO_INFLUENCER_UPDATE_PROFILE,
					Person::SCENARIO_CLIENT_UPDATE,
				]
			],
			[['name', 'city', 'country', 'brand_name'], 'required', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
//			[['name', 'last_name', 'city', 'country', 'brand_name'], 'required', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE], // we have a lot of devisers without last_name... we cannot make it required
			[['name', 'last_name', 'city'], 'required', 'on' => Person::SCENARIO_INFLUENCER_UPDATE_PROFILE],
			[['name', 'last_name'], 'required', 'on' => [Person::SCENARIO_CLIENT_CREATE, Person::SCENARIO_CLIENT_UPDATE]],
		];
	}
}