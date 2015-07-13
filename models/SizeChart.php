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

}