<?php
namespace app\models;

use app\helpers\CActiveRecord;

class Cart extends CActiveRecord {

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
		return 'cart';
	}

	public function attributes() {
		return [
			'_id',
			'product_id',
		];
	}
}
