<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use Yii;
use yii\mongodb\ActiveQuery;

/**
 * @property string country_code
 * @property string country_name
 * @property string currency_code
 * @property string continent
 * @property string path
 */
class Country extends CActiveRecord
{
	const AFRICA = "AF";
	const ANTARCTICA = "AN";
	const ASIA = "AS";
	const EUROPE = "EU";
	const NORTH_AMERICA = "NA";
	const AUSTRALIA = "OC";
	const SOUTH_AMERICA = "SA";
	const WORLD_WIDE = "WW";

	const CONTINENTS = [
		Country::AFRICA => "Africa",
		Country::ANTARCTICA => "Antarctica",
		Country::ASIA => "Asia",
		Country::EUROPE => "Europe",
		Country::NORTH_AMERICA => "North America",
		Country::SOUTH_AMERICA => "South America",
		Country::AUSTRALIA => "Australia",
		Country::WORLD_WIDE => "World Wide"
	];

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


	function __construct()
	{
		parent::__construct();

		Yii::t("app/admin", "Africa");
		Yii::t("app/admin", "Antarctica");
		Yii::t("app/admin", "Asia");
		Yii::t("app/admin", "Europe");
		Yii::t("app/admin", "North America");
		Yii::t("app/admin", "South America");
		Yii::t("app/admin", "Australia");
		Yii::t("app/admin", "World wide");
	}

	public static function collectionName()
	{
		return 'country';
	}


	public function attributes()
	{
		return [
			'_id',
			'country_code',
			'country_name',
			'currency_code',
			'continent',
			'path',
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public static $translatedAttributes = ['country_name'];


	/**
	 * Prepare the ActiveRecord properties to serialize the objects properly, to retrieve an serialize
	 * only the attributes needed for a query context
	 *
	 * @param $view
	 */
	public static function setSerializeScenario($view)
	{
		switch ($view) {
			case self::SERIALIZE_SCENARIO_PUBLIC:
				static::$serializeFields = [
					'id' => 'country_code',
					'country_name',
					'currency_code',
					'continent',
				];
				static::$translateFields = true;
				break;
			case self::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'id' => 'country_code',
					'country_name',
					'currency_code',
					'continent',
				];
				static::$translateFields = false;
				break;
			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
	}

	public function rules()
	{
		return [
			[
				[
					'country_code',
					'country_name',
					'currency_code',
					'continent',
					'path',
				],
				'safe',
			],
			[
				'country_name',
				'app\validators\TranslatableValidator',
			],
		];
	}

	/**
	 * Get one entity serialized
	 *
	 * @param string $id
	 *
	 * @return Country|null
	 */
	public static function findOneSerialized($id)
	{
		/** @var Country $country */
		$country = static::find()->select(self::getSelectFields())->where(["country_code" => $id])->one();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($country);
		}

		return $country;
	}

	/**
	 * Get a collection of entities serialized, according to serialization configuration
	 *
	 * @param array $criteria
	 * @return array
	 */
	public static function findSerialized($criteria = [])
	{

		$query = new ActiveQuery(Country::className());

		// Retrieve only fields that gonna be used
		$query->select(self::getSelectFields());

		// if name is specified
		if ((array_key_exists("name", $criteria)) && (!empty($criteria["name"]))) {
//			// search the word in all available languages
			$query->andFilterWhere(Utils::getFilterForTranslatableField("country_name", $criteria["name"]));
		}

		// if continent is specified
		if ((array_key_exists("continent", $criteria)) && (!empty($criteria["continent"]))) {
			$query->andWhere(["continent" => $criteria['continent']]);
		}

		// if country_code is specified
		if ((array_key_exists("country_code", $criteria)) && (!empty($criteria["country_code"]))) {
			$query->andWhere(["country_code" => $criteria['country_code']]);
		}

		// if person_type is specified
		if ((array_key_exists("person_type", $criteria)) && (!empty($criteria["person_type"]))) {
			// Get different countries available by person type
			$queryPerson= new ActiveQuery(Person::className());
			$queryPerson->andWhere(["type" => (int)$criteria["person_type"]]);
			$queryPerson->andWhere(["account_state" => Person::ACCOUNT_STATE_ACTIVE]);
			$countries = $queryPerson->distinct("personal_info.country");

			$query->andFilterWhere(["in", "country_code", $countries]);
		}

		// if only_with_boxes is specified
		if ((array_key_exists("only_with_boxes", $criteria)) && (!empty($criteria["only_with_boxes"]))) {

			// Get all boxes not empty and actives
			$boxes = Box::findSerialized([
				"ignore_empty_boxes" => true,
				"only_active_persons" => true,
			]);

			$idsPersons = [];
			foreach ($boxes as $box) {
				$idsPersons[] = $box->person_id;
			}

			// Get different countries of persons with boxes
			if ($idsPersons) {
				$queryPerson = new ActiveQuery(Person::className());
				$queryPerson->andWhere(["account_state" => Person::ACCOUNT_STATE_ACTIVE]);
				$queryPerson->andWhere(["short_id" => $idsPersons]);
				$countries = $queryPerson->distinct("personal_info.country");

				$query->andFilterWhere(["in", "country_code", $countries]);
			} else {
				$query->andFilterWhere(["in", "country_code", "dummy_country"]); // Force no results if there are no boxes
			}
		}

		// if only_with_stories is specified
		if ((array_key_exists("only_with_stories", $criteria)) && (!empty($criteria["only_with_stories"]))) {

			// Get all stories not empty and actives
			$stories = Story::findSerialized([
				"story_account_state" => Story::STORY_STATE_ACTIVE,
				"only_active_persons" => true,
			]);

			$idsPersons = [];
			foreach ($stories as $story) {
				$idsPersons[] = $story->person_id;
			}

			// Get different countries of persons with stories
			if ($idsPersons) {
				$queryPerson = new ActiveQuery(Person::className());
				$queryPerson->andWhere(["account_state" => Person::ACCOUNT_STATE_ACTIVE]);
				$queryPerson->andWhere(["short_id" => $idsPersons]);
				$countries = $queryPerson->distinct("personal_info.country");

				$query->andFilterWhere(["in", "country_code", $countries]);
			} else {
				$query->andFilterWhere(["in", "country_code", "dummy_country"]); // Force no results if there are no stories
			}
		}

		// Count how many items are with those conditions, before limit them for pagination
		static::$countItemsFound = $query->count();

		// limit
		if ((array_key_exists("limit", $criteria)) && (!empty($criteria["limit"]))) {
			$query->limit($criteria["limit"]);
		}

		// offset for pagination
		if ((array_key_exists("offset", $criteria)) && (!empty($criteria["offset"]))) {
			$query->offset($criteria["offset"]);
		}

		$items = $query->all();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($items);
		}

		return $items;
	}

	/**
	 * Returns TRUE if the continentCode is valid.
	 * If countryCode is present, it checks that continent and country are both valid
	 *
	 * @param string $continentCode
	 * @param string $countryCode
	 *
	 * @return bool
	 */
	public static function validateContinentCode($continentCode, $countryCode = null) {
		$continents = static::CONTINENTS;
		$valid = isset($continents[$continentCode]);
		if ($countryCode) {
			$country = static::findOne(['country_code' => $countryCode]);
			if (empty($country) || $country->continent != $continentCode) {
				$valid = false;
			}
		}

		return $valid;
	}

	/**
	 * Returns all available country_codes
	 *
	 * @return array
	 */
	public static function getCountryCodes() {
		$countries = Country::findSerialized();
		$country_codes = [];
		foreach  ($countries as $country) {
			$country_codes[] = $country->country_code;
		}

		return $country_codes;
	}

	/**
	 * Returns the code of the default country
	 *
	 * @return string
	 */
	public static function getDefaultContryCode() {
		return "ES";
	}

	/**
	 * Returns a list of available country codes for shipping
	 *
	 * @return array
	 */
	public static function getShippingAvailableCountryCodes() {
		return [
			'ES',
		];
	}

	/**
	 * Returns a list of countries in the European Union
	 *
	 * @return array
	 */
	public static function getEUCountryCodes() {
		return [
			'BE',
			'BG',
			'HR',
			'CZ',
			'DK',
			'DE',
			'EE',
			'IE',
			'EL',
			'ES',
			'FR',
			'IT',
			'CY',
			'LV',
			'LT',
			'LU',
			'HU',
			'MT',
			'NL',
			'AT',
			'PL',
			'PT',
			'RO',
			'SI',
			'SK',
			'FI',
			'SE',
			'UK',
		];
	}

	/**
	 * Returns the list of available countries for shipping
	 *
	 * @return Country[]
	 */
	public static function getShippingCountries() {
		return static::findSerialized([
			'country_code' => static::getShippingAvailableCountryCodes(),
		]);
	}

	/**
	 * Returns an special object containing all the continents and the countries
	 *
	 * @return \stdClass
	 */
	public static function getWorldwide() {

		$continents = [];
		foreach (Country::CONTINENTS as $code => $name) {
			if ($code != Country::WORLD_WIDE) {
				$continent = new \stdClass();
				$continent->code = $code;
				$continent->name = $name;
				$continent->path = Country::WORLD_WIDE.'/'.$code;
				$continent->items = Country::findSerialized(['continent' => $code]);
				$continents[] = $continent;
			}
		}

		$worldwide = new \stdClass();
		$worldwide->code = Country::WORLD_WIDE;
		$worldwide->name = Country::CONTINENTS[Country::WORLD_WIDE];
		$worldwide->path = Country::WORLD_WIDE;
		$worldwide->items = $continents;

		return $worldwide;
	}

	/**
	 * Returns the list of countries in the European Union
	 *
	 * @return Country[]
	 */
	public static function getEUCountries() {
		return static::findSerialized([
			'country_code' => static::getEUCountryCodes(),
		]);
	}
}