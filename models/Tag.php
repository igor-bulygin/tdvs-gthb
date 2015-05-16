<?php
namespace app\models;

use Yii;
use app\helpers\CActiveRecord;

class Option extends CActiveRecord {

	const TYPE_NUMERIC = 0;
	const TYPE_ALPHANUMERIC = 1;
}

class Tag extends CActiveRecord {

	const TYPE_DROPDOWN = 0;
	const TYPE_FREETEXT = 1;

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