<?php
namespace app\models;

use Yii;
use app\helpers\Utils;
use app\helpers\CActiveRecord;
use yii\web\IdentityInterface;
use yii\base\NotSupportedException;

/**
 * @property string deviser_id
 * @property array categories
 * @property array collections
 * @property array name
 * @property array slug
 * @property array description
 * @property array media
 * @property array options
 * @property array madetoorder
 * @property array sizechart
 * @property array bespoke
 * @property array preorder
 * @property array returns
 * @property array warranty
 * @property array currency
 * @property array weight_unit
 * @property array price_stock
 * @property int enabled
 */
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

	public function deletePhotos() {
		$product_path = Utils::join_paths(Yii::getAlias("@product"), $this->short_id);

		Utils::rmdir($product_path);
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
			case CActiveRecord::SERIALIZE_SCENARIO_PUBLIC:
				static::$serializeFields = [
					'id' => 'short_id',
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
				static::$translateFields = true;
				break;
			case CActiveRecord::SERIALIZE_SCENARIO_ADMIN:
				static::$serializeFields = [
					'id' => 'short_id',
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
				static::$translateFields = false;
				break;
			default:
				// now available for this Model
				static::$serializeFields = [];
				break;
		}
	}

	/**
	 * Get the path to main product image
	 * 
	 * @return string
	 */
	public function getMainImage($urlify = true)
	{
		$image = "";
		$fallback = "product_placeholder.png";

		if (isset($this->media) && isset($this->media["photos"])) {
			foreach ($this->media["photos"] as $key => $photo) {
				if (isset($photo["main_product_photo"]) && $photo["main_product_photo"]) {
					$image = $photo["name"];
					break;
				}
			}
		}

		if ($image === "") {
			if (count($this->media["photos"]) == 0) {
				$image = $fallback;
			} else {
				$image = $this->media["photos"][0]["name"];

				if (!empty($image) && !file_exists(Yii::getAlias("@web") . "/" . $this->short_id . "/" . $image )) {
					$imge = $fallback;
				}
			}
		}

		if ($urlify === true) {
			if ($image === $fallback) {
				$image = Yii::getAlias("@web") . "/imgs/" . $image;
			} else {
				$image = Yii::getAlias("@product_url") . "/" . $this->short_id . "/" . $image;
			}
		}

		return $image;
	}

	public function getNameTranslated()
	{
		return 'lala';
	}
}
