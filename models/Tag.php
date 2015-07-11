<?php
namespace app\models;

use Yii;
use app\helpers\CActiveRecord;

class TagOption {
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
		TagOption::ANIMALPRINT => ["class" => "animal-print", "text" => "Animal print", "value" => "animal-print"]
	];

	function __construct() {
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
	}
}

class Tag extends CActiveRecord {
	const DROPDOWN = 0;
	const FREETEXT = 1;

	public static function collectionName() {
		return 'tag';
	}

	public function attributes() {
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

	public function beforeSave($insert) {
		/*
		 * Create empty data holders if they don't exist
		 */
		if($this->name == null) {
			$this["name"] = [];
		}

		if($this->description == null) {
			$this["description"] = [];
		}

		if($this->categories == null) {
			$this["categories"] = [];
		}

		if($this->options == null) {
			$this["options"] = [];
		}

		return parent::beforeSave($insert);
	}

}