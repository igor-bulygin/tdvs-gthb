<?php
namespace app\models;

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

	public static function collectionName() {
		return 'country';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'country_code',
			'country_name',
			'currency_code',
			'continent'
		];
	}
}