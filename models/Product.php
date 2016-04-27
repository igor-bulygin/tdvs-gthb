<?php
namespace app\models;

use Yii;
use app\helpers\CActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

class Bespoke {
	const NO = 0;
	const YES = 1;
}

class MadeToOrder {
	const NONE = 0;
	const DAYS = 1;
}

class Preorder {
	const NO = 0;
	const YES = 1;
}

class Returns {
	const NONE = 0;
	const DAYS = 1;
}

class Warranty {
	const NONE = 0;
	const DAYS = 1;
	const WEEKS = 2;
	const MONTHS = 3;
}

class Product extends CActiveRecord {
	public static function collectionName() {
		return 'product';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'deviser_id',
			'enabled',
			'categories',
			'collections',
			'name',
			'slug',
			'description',
			'media',
			'options',
			'madetoorder',
			'sizechart',
			'bespoke',
			'preorder',
			'returns',
			'warranty',
			'currency',
			'weight_unit',
			'price_stock',

		];
	}

	public function beforeSave($insert) {
		/*
		 * Create empty data holders if they don't exist
		 */
		if($this->categories == null) {
			$this["categories"] = [];
		}

		if($this->collections == null) {
			$this["collections"] = [];
		}

		if($this->name == null) {
			$this["name"] = [];
		}

		if($this->slug == null) {
			$this["slug"] = [];
		}

		if($this->description == null) {
			$this["description"] = [];
		}

		if($this->media == null) {
			$this["media"] = [
				"videos_links" => [],
				"photos" => []
			];
		}

		if($this->options == null) {
			$this["options"] = [];
		}

		if($this->madetoorder == null) {
			$this["madetoorder"] = [];
		}

		if($this->sizechart == null) {
			$this["sizechart"] = [];
		}

		if($this->bespoke == null) {
			$this["bespoke"] = [];
		}

		if($this->preorder == null) {
			$this["preorder"] = [];
		}

		if($this->returns == null) {
			$this["returns"] = [];
		}

		if($this->warranty == null) {
			$this["warranty"] = [];
		}

		if($this->currency == null) {
			$this["currency"] = "";
		}

		if($this->weight_unit == null) {
			$this["weight_unit"] = "";
		}

		if($this->price_stock == null) {
			$this["price_stock"] = [];
		}

		return parent::beforeSave($insert);
	}

}
