<?php
namespace app\helpers;

use Yii;
use Exception;
use yii\mongodb\ActiveRecord;

/**
 * @property string short_id
 */
class CActiveRecord extends ActiveRecord {

    const SERIALIZE_VIEW_PUBLIC = 'serialize_view_public';
    const SERIALIZE_VIEW_ADMIN = 'serialize_view_admin';

    static protected $serializeFields = [];
    static protected $translateFields = true;

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