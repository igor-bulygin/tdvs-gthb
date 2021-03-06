<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use Exception;
use MongoDate;
use Yii;
use yii\mongodb\ActiveQuery;
use yii2tech\ar\position\PositionBehavior;

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
 * @property string currency
 * @property string weight_unit
 * @property array price_stock
 * @property array references
 * @property int position
 * @property MongoDate created_at
 * @property MongoDate updated_at
 * @property int enabled
 * @method   PositionBehavior moveToPosition($position)
 */
class OldProduct extends CActiveRecord {

	const SCENARIO_PRODUCT_OLD_API = 'scenario-product-old-api';
	const SCENARIO_PRODUCT_UPDATE_DRAFT = 'scenario-product-update-draft';

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
			'references',
			'bespoke',
			'preorder',
			'returns',
			'warranty',
			'currency',
			'weight_unit',
			'price_stock',
			'position',
			'created_at',
			'updated_at',
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public static $translatedAttributes = ['name', 'description', 'slug'];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		// initialize attributes
		$this->categories = [];
		$this->collections = [];
		$this->name = [];
		$this->slug = [];
		$this->description = [];
		$this->media = [
			"videos_links" => [],
			"photos" => []
		];
		$this->options = [];
		$this->madetoorder = [];
		$this->sizechart = [];
		$this->bespoke = [];
		$this->preorder = [];
		$this->returns = [];
		$this->warranty = [];
		$this->currency = "";
		$this->weight_unit = "";
		$this->price_stock = [];
		$this->references = [];
		$this->position = 0;

//		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);
	}


	public function beforeSave($insert) {

		if ($insert) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

//		if (empty($this->slug)) {
//			$this->slug = [
//				Lang::EN_US => Slugger::slugify($this->name[Lang::EN_US])
//			];
//		}

		return parent::beforeSave($insert);
	}

	public function behaviors()
	{
		return array_merge(
			parent::behaviors(),
			[
				'positionBehavior' => [
					'class' => PositionBehavior::className(),
					'positionAttribute' => 'position',
					'groupAttributes' => [
						'deviser_id' // multiple lists varying by 'deviser_id'
					],
				],
			]
		);
	}

	/**
	 * Get one entity serialized
	 *
	 * @param string $id
	 *
	 * @return OldProduct|null
	 * @throws Exception
	 */
	public static function findOneSerialized($id)
	{
		/** @var OldProduct $product */
		$product = OldProduct::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

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
		$query = new ActiveQuery(OldProduct::className());

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
			if (is_array($criteria["categories"])) {
				$ids = [];
				foreach ($criteria["categories"] as $categoryId) {
					$category = Category::findOne(["short_id" => $categoryId]);
					if ($category) {
						$ids = array_merge($ids, $category->getShortIds());
					}
				}
			} else {
				$ids = [];
				$category = Category::findOne(["short_id" => $criteria["categories"]]);
				if ($category) {
					$ids = array_merge($ids, $category->getShortIds());
				}
			}
			$query->andWhere(["categories" => $ids]);
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

		$query->orderBy("deviser_id, position");

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
					'bespoke',
					'preorder',
					'returns',
					'warranty',
					'currency',
//					'weight_unit',
					'references',
					'position',
					'options' => 'productOptions',
					'url_images' => 'urlImagesLocation',
					'price_stock',
				];
				static::$retrieveExtraFields = [
					'deviser_id',
					'options',
					'sizechart',

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
	 * Get videos related with this Product
	 *
	 * @return array
	 */
	public function getVideos()
	{
		// temporary, return all deviser videos
		$deviser = Person::findOne(["short_id" => $this->deviser_id]); /** @var Person $deviser */
		return $deviser->videosMapping;

		$videos = [];
		$persons = Person::find()->where(["videos.products" => $this->short_id])->all();
		foreach ($persons as $person) {
			/** @var PersonVideo $video */
			foreach ($person->findVideosByProductId($this->short_id) as $video) {
				$videos[] = $video;
			}
		}
		return $videos;
	}

	/**
	 * Helper to determine the minimum price in stock & price data.
	 *
	 * @return float|null
	 */
	public function getMinimumPrice()
	{
		if (isset($this->price_stock)) {
			$min = null;
			foreach ($this->price_stock as $item) {
				if (!isset($item['available']) || $item['available']) {
					if (!isset($min)) {
						$min = $item['price'];
					}
					$min = min($min, $item['price']);
				}
			}
			return $min;
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
		// add size as a common tag
		if ((isset($this->sizechart)) && (array_key_exists("values", $this->sizechart)) && (count($this->sizechart["values"]) > 0)){
			$tag = new Tag();
			$tag->forceIsSizeTag = true; // TODO Temp attribute, until products options are refactored
			$tag->sizeChart = $this->sizechart; // TODO Temp attribute, until products options are refactored
			$tag->short_id = "size";
			$tag->required = true;
			$tag->name = [Lang::EN_US => "Size", Lang::ES_ES => "Talla", Lang::CA_ES => "Talla"];
			$tag->description = [Lang::EN_US => "Size", Lang::ES_ES => "Talla", Lang::CA_ES => "Talla"];
			$options[] = $tag;

		}
		Tag::setSerializeScenario(Tag::SERIALIZE_SCENARIO_PRODUCT_OPTION);
		Utils::translate($options);
		return $options;
	}

	/**
	 * Get a preview version of a Deviser
	 *
	 * @return array
	 */
	public function getDeviserPreview()
	{
		/** @var Person $deviser */
		$deviser = Person::findOneSerialized($this->deviser_id);
		return $deviser->getPreviewSerialized();
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
		$label = '';
		if (!empty($this->warranty)) {
			$warrantyType = $this->warranty["type"];
			if (($warrantyType != Warranty::NONE) && (array_key_exists("value", $this->warranty))) {
				$label .= $this->warranty["value"] . ' ';
			}
			$label .= Warranty::getDescription($this->warranty["type"]);
		}
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

	public function rules()
	{
		return [
			// the name, email, subject and body attributes are required
			[
				[
					'deviser_id',
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
				],
				'safe',
				'on' => [self::SCENARIO_PRODUCT_OLD_API, self::SCENARIO_PRODUCT_UPDATE_DRAFT]
			],
		];
	}

	/**
	 * Spread data for sub documents
	 *
	 * @param array $data
	 * @param null $formName
	 * @return bool
	 */
	public function load($data, $formName = null)
	{
		$loaded = parent::load($data, $formName);

		// use position behavior method to move it
		if (array_key_exists("position", $data)) {
			$this->moveToPosition($data["position"]);
		}

		return ($loaded);
	}

	/**
	 * Get few attributes to use in preview sections
	 *
	 * @return array
	 */
	public function getPreviewSerialized()
	{
		return [
			"id" => $this->short_id,
			"slug" => $this->slug,
			"name" => $this->name,
			"media" => $this->media,
			"deviser" => $this->getDeviserPreview(),
			'url_image_preview' => $this->getImagePreview128(),
			"url_images" => $this->getUrlImagesLocation(),
		];
	}

}
