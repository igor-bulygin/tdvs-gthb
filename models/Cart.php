<?php
namespace app\models;

use Yii;
use app\helpers\Utils;
use app\helpers\CActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

class Cart extends CActiveRecord {
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
