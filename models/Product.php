<?php
namespace app\models;

use Yii;
use app\helpers\CActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

class Bespoke {
	const NO = 0;
	const YES = 1;
}

class MadeToOrder {
	const NONE = 0;
	const DAYS = 1;
}

class Preorder {
	const NO = 0;
	const YES = 1;
}

class Returns {
	const NONE = 0;
	const DAYS = 1;
}

class Warranty {
	const NONE = 0;
	const DAYS = 1;
}

class Product extends CActiveRecord {
	public static function collectionName() {
		return 'product';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',

		];
	}

}