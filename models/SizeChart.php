<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use yii\base\Exception;
use yii\mongodb\ActiveQuery;


/**
 * @property string short_id
 * @property array name
 * @property boolean enabled
 * @property int type
 * @property string deviser_id
 * @property array categories
 * @property string metric_unit
 * @property array countries
 * @property array columns
 * @property array values
 * @property string country
 */
class SizeChart extends CActiveRecord {

	const TODEVISE = 0;
	const DEVISER = 1;

	const SERIALIZE_SCENARIO_PUBLIC = 'serialize_scenario_public';

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $serializeFields = [];

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $retrieveExtraFields = [];


	public static function collectionName() {
		return 'size_chart';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'name',
			'enabled',
			'type',
			'deviser_id',
			'categories',
			'metric_unit',
			'countries',
			'columns',
			'values',
		];
	}

	public function beforeSave($insert) {

		if (empty($this->short_id)) {
			$this->short_id = Utils::shortID(5);
		}

		/*
		 * Create empty data holders if they don't exist
		 */
		if($this->name == null) {
			$this["name"] = [];
		}

		if($this->type == null) {
			$this["type"] = self::TODEVISE;
		}

		if($this->countries == null) {
			$this["countries"] = [];
		}

		if($this->columns == null) {
			$this["columns"] = [];
		}

		if($this->values == null) {
			$this["values"] = [];
		}

		if($this->categories == null) {
			$this["categories"] = [];
		}

		if($this->metric_unit == null) {
			$this["metric_unit"] = "";
		}

		if ($this->type == self::TODEVISE) {
			$this->deviser_id = null;
		}

		return parent::beforeSave($insert);
	}

	/**
	 * Prepare the ActiveRecord properties to serialize the objects properly, to retrieve an serialize
	 * only the attributes needed for a query context
	 *
	 * @param $view
	 */
	public static function setSerializeScenario($view)
	{
		switch ($view) {
			case self::SERIALIZE_SCENARIO_PUBLIC:
			case self::SERIALIZE_SCENARIO_OWNER:
			case self::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'id' => 'short_id',
					'name',
					'enabled',
					'type',
					'deviser_id',
					'categories',
					'metric_unit',
					'countries' => 'countriesInfo',
					'columns',
					'values'
				];
				static::$retrieveExtraFields = [
					'countries',
					'country',
				];
				static::$translateFields = false;

				break;

			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
	}

	/**
	 * Hack to solve a problem with a migration that inserts a "deviser sizechart" using country value instead of countries array
	 *
	 * @return array
	 */
	public function getCountriesInfo() {
		$countries = $this->countries ?: [];
		if (isset($this->country)) {
			if (!is_array($countries) || !in_array($this->country, $countries)) {
				$countries[] = $this->country;
			}
		}
		return $countries;
	}

	/**
	 * Get a collection of entities serialized, according to serialization configuration
	 *
	 * @param array $criteria
	 * @return array
	 */
	public static function findSerialized($criteria = [])
	{
		$query = new ActiveQuery(self::className());

		// Retrieve only fields that gonna be used
		$query->select(self::getSelectFields());

		if (array_key_exists("deviser_id", $criteria)) {
			$query->andFilterWhere(
				['or',
					['type' => self::TODEVISE],
					['deviser_id' => $criteria['deviser_id']],
				]
			);
		} else {
			$query->andFilterWhere(['type' => self::TODEVISE]);
		}

		if (array_key_exists("scope", $criteria)) {
			switch ($criteria["scope"]) {
				case "all":
					// nothing to do here...
					break;
				default:
					break;
			}
		}

		// Count how many items are with those conditions, before limit them for pagination
		static::$countItemsFound = $query->count();

		// limit
		if ((array_key_exists("limit", $criteria)) && (!empty($criteria["limit"])) && $criteria["scope"] != "all") {
			$query->limit($criteria["limit"]);
		}

		// offset for pagination
		if ((array_key_exists("offset", $criteria)) && (!empty($criteria["offset"])) && $criteria["scope"] != "all") {
			$query->offset($criteria["offset"]);
		}

		$items = $query->all();


		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($items);
		}
		return $items;
	}

	/**
	 * Get one entity serialized
	 *
	 * @param string $id
	 *
	 * @return SizeChart|null
	 * @throws Exception
	 */
	public static function findOneSerialized($id)
	{
		/** @var SizeChart $sizeChart */
		$sizeChart = static::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($sizeChart);
		}
		return $sizeChart;
	}

	public function rules()
	{
		return [
			[$this->attributes(), 'safe'],
			[
				'deviser_id',
				'required',
				'when' => function($model) {
					return $model->type == 1;
				}
			],
		];
	}

}