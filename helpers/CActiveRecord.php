<?php
namespace app\helpers;

use Yii;
use Exception;
use yii\mongodb\ActiveRecord;

class CActiveRecord extends ActiveRecord {

	public function genValidID($length = 6) {
		$_found = false;
		$_id = null;
		while ($_found === false) {
			$_id = Utils::shortID($length);
			try {
				$this->short_id = $_id;
				$this->insert();
				$_found = true;
			} catch (Exception $e) {}
		}

		return $_id;
	}

}