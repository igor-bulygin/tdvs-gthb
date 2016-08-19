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


    /**
     * Get an array with Faq entities, serialized for public queries
     *
     * @return array
     */
    public static function getSerializedPublic() {
        $countries = Country::find()->select(['country_code', 'country_name', 'currency_code', 'continent'])->asArray()->all();
        // publish in the language selected by current user
        Utils::l_collection($countries, 'country_name');

        return $countries;
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
}