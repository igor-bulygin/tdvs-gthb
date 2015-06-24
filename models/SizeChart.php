<?php
namespace app\models;

use Yii;
use app\helpers\CActiveRecord;

class SizeChart extends CActiveRecord {
	public static function collectionName() {
		return 'size_chart';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'name',
			'enabled',
			'categories',
			'metric_units',
			'countries',
			'columns',
			'values'
		];
	}

}