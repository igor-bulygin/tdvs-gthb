<?php
namespace app\models;

use app\helpers\CActiveRecord;

//TODO create migration

class StaticText extends CActiveRecord {

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $serializeFields = [];

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $retrieveExtraFields = [];

	public static function collectionName() {
		return 'statictext';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'title',
			'text',
			'static_section'
		];
	}
}
