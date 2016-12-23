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
			'continent'
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

}