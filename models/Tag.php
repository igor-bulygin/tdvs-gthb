<?php
namespace app\models;

use Yii;
use app\helpers\CActiveRecord;

class TagOption {
	const NUMERIC = 0;
	const ALPHANUMERIC = 1;
	const TXT = [
		"Numeric", "Alphanumeric"
	];

	function __construct() {
		Yii::t("app/admin", "Numeric");
		Yii::t("app/admin", "Alphanumeric");
	}
}

class Tag extends CActiveRecord {
	const DROPDOWN = 0;
	const FREETEXT = 1;

	public static function collectionName() {
		return 'tag';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'enabled',
			'required',
			'type',
			'n_options',
			'name',
			'description',
			'categories',
			'options'
		];
	}

}