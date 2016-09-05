<?php
namespace app\models;

use app\models\Warranty;
use Exception;
use Yii;
use app\helpers\Utils;
use app\helpers\CActiveRecord;
use yii\mongodb\ActiveQuery;
use yii\mongodb\Collection;
use yii\mongodb\Connection;
use yii\mongodb\rbac\MongoDbManager;
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
	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	static protected $serializeFields = [];

	/** @var  int */
	static public $countItemsFound = 0;


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

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public $translatedAttributes = ['name', 'description', 'slug'];

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

	/**
	 * Get one entity serialized
	 *
	 * @param string $id
	 * @return Product|null
	 * @throws Exception
	 */
	public static function findOneSerialized($id)
	{
		/** @var Product $product */
		$product = Product::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($product);
		}
		return $product;
	}

	/**
	 * Get a collection of entities serialized, according to serialization configuration
	 *
	 * @param array $criteria
	 * @return array
	 * @throws Exception
	 */
	public static function findSerialized($criteria = [])
	{

		// Products query
		$query = new ActiveQuery(Product::className());

		// Retrieve only fields that gonna be used
		$query->select(self::getSelectFields());

		// if product id is specified
		if ((array_key_exists("id", $criteria)) && (!empty($criteria["id"]))) {
			$query->andWhere(["short_id" => $criteria["id"]]);
		}

		// if deviser id is specified
		if ((array_key_exists("deviser_id", $criteria)) && (!empty($criteria["deviser_id"]))) {
			$query->andWhere(["deviser_id" => $criteria["deviser_id"]]);
		}

		// if categories are specified
		if ((array_key_exists("categories", $criteria)) && (!empty($criteria["categories"]))) {
			$query->andWhere(["categories" => $criteria["categories"]]);
		}

		// if name is specified
		if ((array_key_exists("name", $criteria)) && (!empty($criteria["name"]))) {
//			// search the word in all available languages
			$query->andFilterWhere(Utils::getFilterForTranslatableField("name", $criteria["name"]));
		}

		// if name is specified
		if ((array_key_exists("text", $criteria)) && (!empty($criteria["text"]))) {
			// TODO, find not only in description, in name, and other text attributes to be specified too
//			// search the word in all available languages
			$query->andFilterWhere(Utils::getFilterForTranslatableField("description", $criteria["text"]));
		}

		// Count how many items are with those conditions, before limit them for pagination
		static::$countItemsFound = $query->count();

		// limit
		if ((array_key_exists("limit", $criteria)) && (!empty($criteria["limit"]))) {
			$query->limit($criteria["limit"]);
		}

		// offset for pagination
		if ((array_key_exists("offset", $criteria)) && (!empty($criteria["offset"]))) {
			$query->offset($criteria["offset"]);
		}

		$products = $query->all();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($products);
		}
		return $products;
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
			case self::SERIALIZE_SCENARIO_PREVIEW:
				static::$serializeFields = [
					'id' => 'short_id',
					'name',
					'slug',
					'deviser' => "deviserPreview",
					'url_image_preview' => "imagePreview128",
				];
				static::$retrieveExtraFields = [
					'deviser_id',
				];
				break;
			case self::SERIALIZE_SCENARIO_PUBLIC:
				static::$serializeFields = [
					'id' => 'short_id',
					'deviser' => "deviserPreview",
					'enabled',
					'categories',
//					'collections',
					'name',
					'slug',
					'description',
					'media',
					'madetoorder',
//					'sizechart',
					'bespoke',
					'preorder',
					'returns',
					'warranty',
					'currency',
//					'weight_unit',
					'references',
					'options' => 'productOptions',
					'url_images' => 'urlImagesLocation',
				];
				static::$retrieveExtraFields = [
					'deviser_id',
					'options',
					'price_stock',
				];

				static::$translateFields = true;
				break;
			case self::SERIALIZE_SCENARIO_ADMIN:
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
					'url_images' => 'urlImagesLocation',
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
					$image = $fallback;
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

	/**
	 * Get a resized version of main image, to 128px width
	 *
	 * @return string
	 */
	public function getImagePreview128()
	{
		$image = $this->getMainImage();
		// force max widht
		$url = Utils::url_scheme() . Utils::thumborize($image)->resize(128, 0);
		return $url;
	}


	/**
	 * Helper to determine the minimum price in stock & price data.
	 * In the future, this must be in Product class.
	 *
	 * @return float|null
	 */
	public function getMinimumPrice()
	{
		// TODO find minimum price, not first one
		// some products hasn't price and stock in database !!
		if (isset($this->price_stock)) {
			if (count($this->price_stock) > 0) {
				return $this->price_stock[0]["price"];
			}
		}
		return null;
	}

	/**
	 * Get the URLs of images to use in a gallery
	 *
	 * @return array
	 */
	public function getUrlGalleryImages()
	{
		$images = [];
		if (array_key_exists("photos", $this->media)) {
			foreach ($this->media["photos"] as $imageData) {
				$images[] = Yii::getAlias('@product_url') .'/'.  $this->short_id .'/'. $imageData['name'];
			}
		}

		return $images;
	}

	/**
	 * @param null $level
	 * @return Category
	 */
	public function getCategory($level = null)
	{
		$category = Category::findOne(['short_id' => $this->categories[0]]);
		if ($level) {
			// remove first slash, and find id of second level category
			$ancestors = explode('/', rtrim(ltrim($category->path, '/'), '/'));
			$category_id = (count($ancestors) > $level) ? $ancestors[$level] : $ancestors[count($ancestors)-1];
			$levelCategory = Category::findOne(['short_id' => $category_id]);
		}

		// return the ancestor specified in level param, otherwise, return product category
		return (isset($levelCategory)) ? $levelCategory : $category;
	}

	/**
	 * Get the tag options related with this product
	 *
	 * @return array
	 */
	public function getTags()
	{
		$tags = [];
		foreach ($this->options as $tag_id => $detail) {
			/** @var Tag $tag */
			$tag = Tag::findOne(['short_id' => $tag_id]);
			if ($tag) {
				$tag->setAttribute('options', $this->getProductOptionsForTag($tag_id));
				$tags[] = $tag;
			}
		}
		return $tags;
	}

	/**
	 * Build an structure with all references to be handled by client side.
	 * By reference, we understand a combination of a product, and each options combinations where
	 * the product has stock, for example, "t-shirt = X + color = blue + size = xxl"
	 *
	 * @return array
	 */
	public function getReferences()
	{
		$references = [];
		foreach ($this->price_stock as $stock) {
			$options = [];
			foreach ($stock["options"] as $key => $values) {
				if (isset($values[0])) {
					// remove the array
					$options[$key] = $values[0];
				}
			}
			$references[] = [
				"reference_id" => "temp_random_id_" . uniqid(), // TODO retrieve from database
				"options" => $options,
				"stock" => $stock["stock"],
				"price" => $stock["price"],
			];
		}
		return $references;
	}
	
	/**
	 * Build an structure with all references to be handled by client side.
	 * By reference, we understand a combination of a product, and each options combinations where
	 * the product has stock, for example, "t-shirt = X + color = blue + size = xxl"
	 *
	 * @return array
	 */
	public function getProductOptions()
	{
		$options = [];
		foreach ($this->options as $tag_id => $option) {
			/** @var Tag $tag */
			$tag = Tag::findOne(["short_id" => $tag_id]);
			if ($tag) {
				$tag->setFilterProduct($this);
				$options[] = clone $tag;
			}
		}
		Tag::setSerializeScenario(Tag::SERIALIZE_SCENARIO_PRODUCT_OPTION);
		Utils::translate($options);
		return $options;
	}

	/**
	 * Get a preview version of a Deviser
	 *
	 * @return Person
	 */
	public function getDeviserPreview()
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PREVIEW);
		$deviser = Person::findOne(["short_id" => $this->deviser_id]);
		return $deviser;
	}

	/**
	 * @deprecated
	 * @param $tag_id
	 * @return array
	 */
	private function getProductOptionsForTag($tag_id)
	{
		$values = [];
		foreach ($this->options as $id => $option) {
			if ($id == $tag_id) {
				if (count($option)>0) {
					$value = $option[0][0];
					$values[] = ["text" => ["en-US" => $value], "value" => $value];
				}
			}
		}
		return $values;
	}

	/**
	 * Get label to show warranty conditions
	 *
	 * @return string
	 */
	public function getWarrantyLabel()
	{
		$warrantyType = $this->warranty["type"];
		$label = '';
		if (($warrantyType != Warranty::NONE) && (array_key_exists("value", $this->warranty))) {
			$label .= $this->warranty["value"] . ' ';
		}
		$label .= Warranty::getDescription($this->warranty["type"]);
		return $label;
	}

	/**
	 * Get the url to get the images of a Deviser
	 *
	 * @return string
	 */
	public function getUrlImagesLocation()
	{
		return Yii::getAlias("@product_url") . "/" . $this->short_id . "/";
	}

}
