<?php
namespace app\helpers;

use Yii;
use Exception;
use yii\mongodb\ActiveRecord;

/**
 * @property mixed _id
 * @property string short_id
 */
class CActiveRecord extends ActiveRecord {

    const SERIALIZE_SCENARIO_PUBLIC = 'serialize_scenario_public';
    const SERIALIZE_SCENARIO_OWNER = 'serialize_scenario_owner';
    const SERIALIZE_SCENARIO_ADMIN = 'serialize_scenario_admin';

    /**
     * The attributes that should be serialized
     *
     * @var array
     */
    static protected $serializeFields = [];

    /**
     * Determine if serialization have to translate "translatable" attributes automatically
     *
     * @var bool
     */
    static protected $translateFields = true;

    /**
     * The attributes that should be translatable
     *
     * @var array
     */
    public $translatedAttributes = [];

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

    public function fields()
    {
        return static::$serializeFields;
    }

}