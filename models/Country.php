<?php
namespace app\models;

use app\helpers\Utils;
use Yii;
use app\helpers\CActiveRecord;

class Country extends CActiveRecord {
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

	function __construct() {
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

	public static function collectionName() {
		return 'country';
	}


    public function attributes() {
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
    public $translatedAttributes = ['country_name'];


    /**
     * Prepare the ActiveRecord properties to serialize the objects properly, to retrieve an serialize
     * only the attributes needed for a query context
     *
     * @param $view
     */
    public static function setSerializeScenario($view)
    {
        switch ($view) {
            case CActiveRecord::SERIALIZE_SCENARIO_PUBLIC:
                static::$serializeFields = [
                    'id' => 'country_code',
                    'country_name',
                    'currency_code',
                    'continent',
                ];
                static::$translateFields = true;
                break;
            case CActiveRecord::SERIALIZE_SCENARIO_ADMIN:
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
     * @return array
     */
    public static function getSerialized() {

        // retrieve only fields that want to be serialized
        $faqs = Country::find()->select(array_values(static::$serializeFields))->all();

        // if automatic translation is enabled
        if (static::$translateFields) {
            Utils::translate($faqs);
        }
        return $faqs;
    }

}