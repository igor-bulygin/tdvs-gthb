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

	public function getFaqs($current_path = null) {
		return $this->faqs;
		//
		// if ($current_path === null) {
		// 	$current_path = $this->path . $this->short_id . "/";
		// }
		//
		// return (new Query)->
		// 	select([])->
		// 	from('faqs')->
		// 	where(["REGEX", "path", "/^$current_path/"])->all();
	}

	public function beforeSave($insert) {

		if($this->faqs == null) {
			$this["faqs"] = [];
		}

		return parent::beforeSave($insert);
	}



}
