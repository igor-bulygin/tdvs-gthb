<?php
namespace app\models;

use Yii;
use app\helpers\CActiveRecord;

class SizeChart extends CActiveRecord {
	const TODEVISE = 0;
	const DEVISER = 1;

	public static function collectionName() {
		return 'size_chart';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'name',
			'enabled',
			'type',
			'deviser_id',
			'categories',
			'metric_unit',
			'countries',
			'columns',
			'values'
		];
	}

	public function beforeSave($insert) {
		/*
		 * Create empty data holders if they don't exist
		 */
		if($this->name == null) {
			$this["name"] = [];
		}

		if($this->type == null) {
			$this["type"] = 0;
		}

		if($this->countries == null) {
			$this["countries"] = [];
		}

		if($this->columns == null) {
			$this["columns"] = [];
		}

		if($this->values == null) {
			$this["values"] = [];
		}

		if($this->categories == null) {
			$this["categories"] = [];
		}

		if($this->metric_unit == null) {
			$this["metric_unit"] = "";
		}

		return parent::beforeSave($insert);
	}

}