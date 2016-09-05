<?php
namespace app\models;

use app\helpers\Utils;
use Exception;
use Yii;
use app\helpers\CActiveRecord;

class TagOption
{
	const NUMERIC = 0;
	const ALPHANUMERIC = 1;
	const TXT = [
		"Numeric", "Alphanumeric"
	];

	const DARKRED = 0;
	const RED = 1;
	const ORANGE = 2;
	const BROWN = 3;
	const YELLOW = 4;
	const GREEN = 5;
	const DARKGREEN = 6;
	const BLUE = 7;
	const DARKBLUE = 8;
	const PURPLE = 9;
	const PINK = 10;
	const WHITE = 11;
	const GREY = 12;
	const BLACK = 13;
	const ANIMALPRINT = 14;
	const GOLD = 15;
	const CREAM = 16;
	const SILVER = 17;
	const LIGHTGREEN = 18;

	const COLORS = [
		TagOption::DARKRED => ["class" => "dark-red", "text" => "Dark red", "value" => "dark-red"],
		TagOption::RED => ["class" => "red", "text" => "Red", "value" => "red"],
		TagOption::ORANGE => ["class" => "orange", "text" => "Orange", "value" => "orange"],
		TagOption::BROWN => ["class" => "brown", "text" => "Brown", "value" => "brown"],
		TagOption::YELLOW => ["class" => "yellow", "text" => "Yellow", "value" => "yellow"],
		TagOption::GREEN => ["class" => "green", "text" => "Green", "value" => "green"],
		TagOption::DARKGREEN => ["class" => "dark-green", "text" => "Dark green", "value" => "dark-green"],
		TagOption::BLUE => ["class" => "blue", "text" => "Blue", "value" => "blue"],
		TagOption::DARKBLUE => ["class" => "dark-blue", "text" => "Dark blue", "value" => "dark-blue"],
		TagOption::PURPLE => ["class" => "purple", "text" => "Purple", "value" => "purple"],
		TagOption::PINK => ["class" => "pink", "text" => "Pink", "value" => "pink"],
		TagOption::WHITE => ["class" => "white", "text" => "White", "value" => "white"],
		TagOption::GREY => ["class" => "grey", "text" => "Grey", "value" => "grey"],
		TagOption::BLACK => ["class" => "black", "text" => "Black", "value" => "black"],
		TagOption::ANIMALPRINT => ["class" => "animal-print", "text" => "Animal print", "value" => "animal-print"],
		TagOption::GOLD => ["class" => "gold", "text" => "Gold", "value" => "gold"],
		TagOption::CREAM => ["class" => "cream", "text" => "Cream", "value" => "cream"],
		TagOption::SILVER => ["class" => "silver", "text" => "Silver", "value" => "silver"],
		TagOption::LIGHTGREEN => ["class" => "lightgreen", "text" => "Light green", "value" => "lightgreen"]
	];

	function __construct()
	{
		Yii::t("app/admin", "Numeric");
		Yii::t("app/admin", "Alphanumeric");

		Yii::t("app/admin", "Dark red");
		Yii::t("app/admin", "Red");
		Yii::t("app/admin", "Orange");
		Yii::t("app/admin", "Brown");
		Yii::t("app/admin", "Yellow");
		Yii::t("app/admin", "Green");
		Yii::t("app/admin", "Dark green");
		Yii::t("app/admin", "Blue");
		Yii::t("app/admin", "Dark blue");
		Yii::t("app/admin", "Purple");
		Yii::t("app/admin", "Pink");
		Yii::t("app/admin", "White");
		Yii::t("app/admin", "Black");
		Yii::t("app/admin", "Animal print");
		Yii::t("app/admin", "Gold");
		Yii::t("app/admin", "Cream");
		Yii::t("app/admin", "Silver");
		Yii::t("app/admin", "Light green");
	}
}

/**
 * @property string slug
 * @property bool enabled
 * @property bool required
 * @property bool stock_and_price
 * @property int type
 * @property int n_options
 * @property array name
 * @property array description
 * @property array categories
 * @property array options
 */
class Tag extends CActiveRecord
{
	const DROPDOWN = 0;
	const FREETEXT = 1;

	const SERIALIZE_SCENARIO_PRODUCT_OPTION = 'serialize_scenario_product_option';

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	static protected $serializeFields = [];

	/** @var  Product */
	private $product;

	public static function collectionName()
	{
		return 'tag';
	}

	public function attributes()
	{
		return [
			'_id',
			'short_id',
			'enabled',
			'required',
			'stock_and_price',
			'type',
			'n_options',
			'name',
			'description',
			'categories',
			'options'
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public $translatedAttributes = ['name', 'description'];


	public function beforeSave($insert)
	{
		/*
		 * Create empty data holders if they don't exist
		 */
		if ($this->name == null) {
			$this["name"] = [];
		}

		if ($this->description == null) {
			$this["description"] = [];
		}

		if ($this->categories == null) {
			$this["categories"] = [];
		}

		if ($this->options == null) {
			$this["options"] = [];
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
			case self::SERIALIZE_SCENARIO_PRODUCT_OPTION:
				static::$serializeFields = [
					'id' => 'short_id',
					'widget_type' => 'widgetType',
					'required',
					'name',
					'description',
					'change_reference' => 'changeReference',
					'values' => 'productValues',
				];
				static::$retrieveExtraFields = [
					'options',
				];
				static::$translateFields = true;
				break;
			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
	}

	/**
	 * Get the values available for a Product
	 * @return array
	 */
	public function getProductValues()
	{
		$values = [];

		foreach ($this->options as $key => $option) {
//			print_r($this->product->options["731ct"]);
//			if ($key < 5) {
			// TODO Be careful with "two colors" widget
			if (!empty($this->product->options[$this->short_id][0])) {
				if ($this->product->options[$this->short_id][0][0] == $option["value"]) {
					$values[] = [
						"value" => $option["value"],
						"text" => Utils::l($option["text"]),
						"hint" => null,
						"image" => null,
						"default" => null,
						"colors" => [],
					];
				}
			}
		}

		return $values;
	}

	/**
	 * Get the widget that must be used to select the value
	 *
	 * @return string
	 */
	public function getWidgetType()
	{
		return "select";
	}

	/**
	 * Indicate if this tag / option change the product reference, therefore, could change price and stock
	 *
	 * @return string
	 */
	public function getChangeReference()
	{
		return (isset($this->stock_and_price)) ? $this->stock_and_price : false;
	}

	/**
	 * Set the product to use to filter the list of values to show
	 *
	 * @param Product $product
	 * @return Tag
	 */
	public function setFilterProduct(Product $product)
	{
		$this->product = $product;
		return $this;
	}

}
