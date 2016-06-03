<?php
namespace app\models;

use Yii;
use app\helpers\Utils;
use app\helpers\CActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

//TODO create migration

class StaticText extends CActiveRecord {
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
