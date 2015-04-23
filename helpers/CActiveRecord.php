<?php
namespace app\helpers;

use Yii;
use Exception;
use yii\db\Query;
use yii\mongodb\ActiveRecord;

class CActiveRecord extends ActiveRecord {

	public function genValidID() {
		$_found = false;
		$_id = null;
		while ($_found === false) {
			$_id = Utils::shortID(6);
			try {
				$this->getCollection()->insert(['short_id' => $_id]);
				$_found = true;
			} catch (Exception $e) {}
		}

		return $_id;
	}

}