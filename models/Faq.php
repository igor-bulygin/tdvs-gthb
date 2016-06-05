<?php
namespace app\models;

use Yii;
use app\helpers\Utils;
use app\helpers\CActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

class Faq extends CActiveRecord {
	public static function collectionName() {
		return 'faq';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'title',
			'faqs',
		];
	}
}
