<?php
namespace app\helpers;

use Yii;
use Exception;
use yii2tech\embedded\mongodb\ActiveRecord;

/**
 * @property mixed _id
 * @property string short_id
 */
class CActiveRecord extends ActiveRecord
{

	const SERIALIZE_SCENARIO_PREVIEW = 'serialize_scenario_preview';
	const SERIALIZE_SCENARIO_PUBLIC = 'serialize_scenario_public';
	const SERIALIZE_SCENARIO_OWNER = 'serialize_scenario_owner';
	const SERIALIZE_SCENARIO_ADMIN = 'serialize_scenario_admin';
	const SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT = 'serialize_scenario_load_sub_document';

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	static protected $serializeFields = [];

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	static protected $retrieveExtraFields = [];

	/** @var  int */
	static public $countItemsFound = 0;

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

	public function genValidID($length = 6)
	{
		$_found = false;
		$_id = null;
		while ($_found === false) {
			$_id = Utils::shortID($length);
			try {
				$this->short_id = $_id;
				$this->insert();
				$_found = true;
			} catch (Exception $e) {
			}
		}

		return $_id;
	}

	/**
	 * Get list of fields that want to be serialized
	 *
	 * @return array
	 */
	public function fields()
	{
		return static::$serializeFields;
	}

	/**
	 * Get list of fields that want to be retrieved from database. There will be only fields that will be used
	 * in serialization (directly, or thru getters).
	 *
	 * @return array
	 */
	static public function getSelectFields()
	{
		// fields that want to be serialized, and extra fields for internal use
		return array_merge(array_values(static::$serializeFields), static::$retrieveExtraFields);
	}

}