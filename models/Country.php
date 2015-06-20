<?php
namespace app\models;

use Yii;
use app\helpers\CActiveRecord;

class Country extends CActiveRecord {
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