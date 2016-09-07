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

	const HEXADECIMAL_COLORS = [
		TagOption::DARKRED => "#ca2440",
		TagOption::RED => "#ff6262",
		TagOption::ORANGE => "#f7a128",
		TagOption::BROWN => "#936524",
		TagOption::YELLOW => "#f7f028",
		TagOption::GREEN => "#8ee04b",
		TagOption::DARKGREEN => "#57a617",
		TagOption::BLUE => "#28d0f7",
		TagOption::DARKBLUE => "#2861f7",
		TagOption::PURPLE => "#b933f4",
		TagOption::PINK => "#ff9ce4",
		TagOption::WHITE => "#fff",
		TagOption::GREY => "#bfbfbf",
		TagOption::BLACK => "#2e2e2e",
		TagOption::ANIMALPRINT => "",
		TagOption::GOLD => "#ffd55d",
		TagOption::CREAM => "#ffe6bd",
		TagOption::SILVER => "#c0c0c0",
		TagOption::LIGHTGREEN => "#90ee90",
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

	/** @var  bool */
	public $forceIsSizeTag = false;

	/** @var array */
	public $sizeCart = [];

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

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		// initialize attributes
		$this->name = [];
		$this->description = [];
		$this->categories = [];
		$this->options = [];

		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);
	}

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

		if ($this->forceIsSizeTag) {
			foreach ($this->sizeCart["values"] as $key => $option) {
				if (count($option) > 0) {
					$values[] = [
						"value" => ($key+1),
						"text" => $option[0],
						"hint" => null,
						"image" => null,
						"default" => null,
						"colors" => [],
					];
				}
			}
		} else {
			foreach ($this->options as $key => $option) {
				if (!empty($this->product->options[$this->short_id][0])) {
					if ($this->product->options[$this->short_id][0][0] == $option["value"]) {
						$values[] = [
							"value" => $option["value"],
							"text" => Utils::l($option["text"]),
							"hint" => null,
							"image" => null,
							"default" => null,
							"colors" => $this->getOptionColor($option),
						];
					}
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
		// TODO refactor size like other product tag / option
		if ($this->forceIsSizeTag) {
			return "size";
		}

		// TODO refactor the Tag attributes, to known easily what kind of widget must to be used to select
		if ((is_array($this->options)) && (count($this->options)>0) && ($this->options[0])) {
			$firstValue = $this->options[0];
			if ((array_key_exists("is_color",  $firstValue)) && ($firstValue["is_color"])) {
				return "color";
			}
		}
		return "select";
	}

	/**
	 * @param $option
	 * @return array
	 */
	public function getOptionColor($option)
	{
		if ($this->getWidgetType()=='color') {
			return [TagOption::HEXADECIMAL_COLORS[rand(0, count(TagOption::HEXADECIMAL_COLORS)-1)]];
		} else {
			return [];
		}
	}

	/**
	 * Indicate if this tag / option change the product reference, therefore, could change price and stock
	 *
	 * @return string
	 */
	public function getChangeReference()
	{
		if ($this->forceIsSizeTag) {
			return true;
		}
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
