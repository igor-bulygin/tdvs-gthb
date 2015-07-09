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
			'stock_and_price',
			'type',
			'n_options',
			'name',
			'description',
			'categories',
			'options'
		];
	}

	public function beforeSave($insert) {
		/*
		 * Create empty data holders if they don't exist
		 */
		if($this->name == null) {
			$this["name"] = [];
		}

		if($this->description == null) {
			$this["description"] = [];
		}

		if($this->categories == null) {
			$this["categories"] = [];
		}

		if($this->options == null) {
			$this["options"] = [];
		}

		return parent::beforeSave($insert);
	}

}