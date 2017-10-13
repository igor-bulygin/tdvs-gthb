<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use EasySlugger\Slugger;
use Exception;
use MongoDate;
use Yii;
use yii\helpers\FileHelper;
use yii\helpers\Url;
use yii\mongodb\ActiveQuery;
use yii2tech\ar\position\PositionBehavior;

/**
 * @property string deviser_id
 * @property array name
 * @property array slug
 * @property array description
 * @property array categories
 * @property ProductMedia $mediaMapping
 * @property Preorder $preorderMapping
 * @property MadeToOrder $madeToOrderMapping
 * @property Bespoke $bespokeMapping
 * @property FaqQuestion[] $faqMapping
*
 * @property array collections
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
 * @property string dimension_unit
 * @property array price_stock
 * @property array tags
 * @property array references
 * @property string $product_state
 * @property int position
 * @property int loveds
 * @property int boxes
 * @property array prints
 * @property MongoDate created_at
 * @property MongoDate updated_at
 * @property int enabled
 * @method   PositionBehavior moveToPosition($position)
 */
class Product extends CActiveRecord {

	const PRODUCT_STATE_DRAFT = 'product_state_draft';
	const PRODUCT_STATE_ACTIVE = 'product_state_active';

	const SCENARIO_PRODUCT_OLD_API = 'scenario-product-old-api';
	const SCENARIO_PRODUCT_DRAFT = 'scenario-product-draft';
	const SCENARIO_PRODUCT_PUBLIC = 'scenario-product-public';

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
			'faq',
			'product_state',
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
			'dimension_unit',
			'price_stock',
			'tags',
			'position',
			'loveds',
			'boxes',
			'prints',
			'created_at',
			'updated_at',
		];
	}

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public static $translatedAttributes = ['name', 'description', 'slug', 'tags', 'faq.question', 'faq.answer', 'media.description_photos.title', 'media.description_photos.description'];

	public static $textFilterAttributes = ['name', 'description', 'tags'];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();

		$this->short_id = Utils::shortID(7);

		// initialize attributes
		$this->categories = [];
		$this->collections = [];
		$this->name = [];
		$this->slug = [];
		$this->description = [];
		$this->options = [];
		$this->madetoorder = [];
		$this->sizechart = [];
		$this->bespoke = [];
		$this->preorder = [];
		$this->returns = [];
		$this->warranty = [];
		$this->currency = "";
		$this->weight_unit = "";
		$this->dimension_unit = "";
		$this->price_stock = [];
		$this->tags = [];
		$this->references = [];
		$this->position = 0;
		$this->loveds = 0;
		$this->boxes = 0;

		$this->faq = [];
	}

	public function embedMediaMapping()
	{
		return $this->mapEmbedded('media', ProductMedia::className(), array('unsetSource' => false));
	}

	public function embedPreorderMapping()
	{
		return $this->mapEmbedded('preorder', Preorder::className(), array('unsetSource' => false));
	}

	public function embedMadeToOrderMapping()
	{
		return $this->mapEmbedded('madetoorder', MadeToOrder::className(), array('unsetSource' => false));
	}

	public function embedBespokeMapping()
	{
		return $this->mapEmbedded('bespoke', Bespoke::className(), array('unsetSource' => false));
	}

	public function embedFaqMapping()
	{
		return $this->mapEmbeddedList('faq', FaqQuestion::className(), array('unsetSource' => false));
	}

	public function setParentOnEmbbedMappings()
	{
		$this->mediaMapping->setParentObject($this);
		$this->preorderMapping->setParentObject($this);
		$this->madeToOrderMapping->setParentObject($this);
		$this->bespokeMapping->setParentObject($this);
		foreach ($this->faqMapping as $faqMapping) {
			$faqMapping->setParentObject($this);
		}
	}

	public function validate($attributeNames = null, $clearErrors = true)
	{
		if (is_array($attributeNames) && !empty($attributeNames)) {
			if (in_array('media', $attributeNames)) {
				$attributeNames[] = 'mediaMapping';
			}
			if (in_array('faq', $attributeNames)) {
				$attributeNames[] = 'faqMapping';
			}
			if (in_array('preorder', $attributeNames)) {
				$attributeNames[] = 'preorderMapping';
			}
			if (in_array('madetoorder', $attributeNames)) {
				$attributeNames[] = 'madeToOrderMapping';
			}
			if (in_array('bespoke', $attributeNames)) {
				$attributeNames[] = 'bespokeMapping';
			}
		}
		return parent::validate($attributeNames, $clearErrors);
	}

	public function afterSave($insert, $changedAttributes) {
		if ($insert) {
			$this->moveTempUploadsToProductPath();
		}

		parent::afterSave($insert, $changedAttributes);
	}

	public function beforeSave($insert)
	{
		// short_id on price_stock
		$priceStock = $this->price_stock;
		foreach ($priceStock as $k => $item) {
			if (!isset($item['short_id'])) {
				$priceStock[$k]['short_id'] = $this->short_id . Utils::shortID(7);
			}
		}
		$this->setAttribute('price_stock', $priceStock);

		$slugs = [];
		foreach (Lang::getAvailableLanguages() as $lang => $name) {
			if (isset($this->name[$lang])) {
				$slugs[$lang] = Slugger::slugify($this->name[$lang]);
			} elseif (isset($this->name[Lang::EN_US])) {
				// By default english
				$slugs[$lang] = Slugger::slugify($this->name[Lang::EN_US]);
			}
		}
		$this->setAttribute("slug", $slugs);

		if (empty($this->product_state)) {
			$this->product_state = Product::PRODUCT_STATE_DRAFT;
		}

		if (!isset($this->loveds)) {
			$this->loveds = 0;
		}

		if (!isset($this->boxes)) {
			$this->boxes = 0;
		}

		if ($insert) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	public function beforeDelete() {

		if ($this->hasOrders()) {
			throw new Exception("You cannot delete a product with orders");
		}

		$this->deletePhotos();

		$loveds = $this->getLoveds();
		foreach ($loveds as $loved) {
			$loved->delete();
		}

		$boxes = $this->getBoxes();
		foreach ($boxes as $box) {
			$box->deleteProduct($this->short_id);
		}

		return parent::beforeDelete();
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

	public function rules()
	{
		return [
			[
				[
					'deviser_id',
				],
				'required',
				'on' => [self::SCENARIO_PRODUCT_DRAFT]
			],
			[
				[
					'deviser_id',
					'name',
					'categories',
					'description',
				],
				'required',
				'on' => [self::SCENARIO_PRODUCT_PUBLIC],
			],
			[
				[
					'name',
					'description',
				],
				'app\validators\TranslatableRequiredValidator',
				'on' => self::SCENARIO_PRODUCT_PUBLIC,
			],
			[
				[
					'name',
				],
				'app\validators\SpacesFilterValidator',
			],
			[
				[
					'deviser_id',
					'name',
					'slug',
					'description',
					'categories',
					'faq',
					'collections',
					'options',
					'madetoorder',
					'sizechart',
					'bespoke',
					'preorder',
					'returns',
					'warranty',
					'currency',
					'weight_unit',
					'dimension_unit',
					'price_stock',
					'tags',
					'position',
					'loveds',
					'boxes',
					'prints',
					'product_state',
				],
				'safe',
				'on' => [self::SCENARIO_PRODUCT_OLD_API, self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC]
			],
			[
				'position',
				'integer',
				'min' => 0,
			],
			[
				'product_state',
				'in',
				'range' => [self::PRODUCT_STATE_DRAFT, self::PRODUCT_STATE_ACTIVE],
			],
			[
				'name',
				'app\validators\TranslatableValidator',
				'on' => [self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
			],
			[
				'description',
				'app\validators\TranslatableValidator',
				'on' => [self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
			],
			[
				'categories',
				'app\validators\CategoriesValidator',
				'on' => [self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
			],
			[   'media', 'safe'], // to load data posted from WebServices
			[   'mediaMapping', 'app\validators\EmbedDocValidator'], // to apply rules
			[   'faq', 'safe'], // to load data posted from WebServices
			[   'faqMapping', 'app\validators\EmbedDocValidator'], // to apply rules
			[   'preorder', 'safe'], // to load data posted from WebServices
			[   'preorderMapping', 'app\validators\EmbedDocValidator'], // to apply rules
			[   'madetoorder', 'safe'], // to load data posted from WebServices
			[   'madeToOrderMapping', 'app\validators\EmbedDocValidator'], // to apply rules
			[   'bespoke', 'safe'], // to load data posted from WebServices
			[   'bespokeMapping', 'app\validators\EmbedDocValidator'], // to apply rules
			[
				'options',
				'app\validators\OptionsValidator',
				'on' => [self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
			],
			[
				'price_stock',
				'app\validators\PriceStockValidator',
				'on' => [self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
			],
			[
				'weight_unit',
				'app\validators\WeightUnitValidator',
				'on' => [self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
			],
			[
				'dimension_unit',
				'app\validators\DimensionUnitValidator',
				'on' => [self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
			],
			[
				'returns',
				'app\validators\ReturnsValidator',
				'on' => [self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
			],
			[
				'warranty',
				'app\validators\WarrantyValidator',
				'on' => [self::SCENARIO_PRODUCT_DRAFT, self::SCENARIO_PRODUCT_PUBLIC],
			],
		];
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
					'slug',
					'name',
					'media',
					'deviser' => "deviserPreview",
					'main_photo' => 'mainImage',
					'main_photo_128' => "imagePreview128",
					'main_photo_256' => "imagePreview256",
					'main_photo_512' => "imagePreview512",
					'main_photo_256_fill' => "imagePreview256Fill",
					'url_images' => 'urlImagesLocation',
					'link' => 'viewLink',
					'edit_link' => 'editLink',
					'isLoved' => 'isLoved',
					'isMine' => 'isMine',
					'min_price' => 'minimumPrice',
				];
				static::$retrieveExtraFields = [
					'deviser_id',
				];

				static::$translateFields = true;
				break;
			case self::SERIALIZE_SCENARIO_PUBLIC:
				static::$serializeFields = [
					'id' => 'short_id',
					'deviser' => "deviserPreview",
					'name',
					'slug',
					'description',
					'categories',
					'media',
					'faq',
					'product_state',
					'enabled',
					'collections',
					'madetoorder',
					'bespoke',
					'preorder',
					'returns',
					'warranty',
					'currency',
					'weight_unit',
					'dimension_unit',
					'references',
					'options' => 'productOptions',
					'url_images' => 'urlImagesLocation',
					'position',
					'loveds',
					'isLoved' => 'isLoved',
					'isMine' => 'isMine',
					'boxes',
					'prints',
					'sizechart',
					'price_stock',
					'tags',
					'link' => 'viewLink',
					'edit_link' => 'editLink',
					'main_photo' => 'mainImage',
					'main_photo_128' => "imagePreview128",
					'main_photo_256' => "imagePreview256",
					'main_photo_512' => "imagePreview512",
					'main_photo_256_fill' => "imagePreview256Fill",
					'min_price' => 'minimumPrice',
				];
				static::$retrieveExtraFields = [
					'deviser_id',
					'options',
					'sizechart',
				];

				static::$translateFields = true;
				break;
			case self::SERIALIZE_SCENARIO_OWNER:
				static::$serializeFields = [
					'id' => 'short_id',
					'deviser' => "deviserPreview",
					'name',
					'slug',
					'description',
					'categories',
					'media',
					'faq',
					'product_state',
					'enabled',
					'collections',
					'madetoorder',
					'bespoke',
					'preorder',
					'returns',
					'warranty',
					'currency',
					'weight_unit',
					'dimension_unit',
					'references',
					'options',
					'sizechart',
					'url_images' => 'urlImagesLocation',
					'position',
					'loveds',
					'boxes',
					'prints',
					'price_stock',
					'tags',
				];
				static::$retrieveExtraFields = [
					'deviser_id',
//
				];

				static::$translateFields = false;
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
					'dimension_unit',
					'price_stock',
					'tags',
					'url_images' => 'urlImagesLocation',
					'product_state',
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
	 * Get one entity serialized
	 *
	 * @param string $id
	 *
	 * @return Product|null
	 * @throws Exception
	 */
	public static function findOneSerialized($id)
	{
		/** @var Product $product */
		$product = static::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

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
	 * @return Product[]
	 * @throws Exception
	 */
	public static function findSerialized($criteria = [])
	{

		// Products query
		$query = new ActiveQuery(static::className());

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

		// if product_state is specified
		if ((array_key_exists("product_state", $criteria)) && (!empty($criteria["product_state"]))) {
			$query->andWhere(["product_state" => $criteria["product_state"]]);
		}
		// if only_active_persons are specified
		if ((array_key_exists("only_active_persons", $criteria)) && (!empty($criteria["only_active_persons"]))) {

			// Get different person_ids available by country
			$queryPerson= new ActiveQuery(Person::className());
			$queryPerson->andWhere(["account_state" => Person::ACCOUNT_STATE_ACTIVE]);
			$idsPerson = $queryPerson->distinct("short_id");

			if ($idsPerson) {
				$query->andFilterWhere(["in", "deviser_id", $idsPerson]);
			} else {
				$query->andFilterWhere(["in", "deviser_id", "dummy_person"]); // Force no results if there are no boxes
			}
		}

		// if name is specified
		if ((array_key_exists("name", $criteria)) && (!empty($criteria["name"]))) {
//			// search the word in all available languages
			$query->andFilterWhere(Utils::getFilterForTranslatableField("name", $criteria["name"]));
		}

		// if text is specified
		if ((array_key_exists("text", $criteria)) && (!empty($criteria["text"]))) {
//			// search the word in all available languages
			$query->andFilterWhere(static::getFilterForText(static::$textFilterAttributes, $criteria["text"]));
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

		// order
		if ((array_key_exists("order_type", $criteria)) && (!empty($criteria["order_type"]))) {
			switch ($criteria['order_type']) {
				case 'new':
					$criteria['order_col'] = 'created_at';
					$criteria['order_dir'] = 'desc';
					break;
				case 'old':
					$criteria['order_col'] = 'created_at';
					$criteria['order_dir'] = 'asc';
					break;
				case 'cheapest':
					$criteria['order_col'] = 'price_stock.price';
					$criteria['order_dir'] = 'asc';
					break;
				case 'expensive':
					$criteria['order_col'] = 'price_stock.price';
					$criteria['order_dir'] = 'desc';
					break;
			}
		}

		if ((array_key_exists("order_col", $criteria)) && (!empty($criteria["order_col"])) &&
			(array_key_exists("order_dir", $criteria)) && (!empty($criteria["order_dir"]))) {
			$query->orderBy([
				$criteria["order_col"] => $criteria["order_dir"] == 'desc' ? SORT_DESC : SORT_ASC,
			]);
		} else {
			$query->orderBy("deviser_id, position");
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
	 * Get the path to main product image
	 *
	 * @return string
	 */
	public function getMainImage()
	{
		$image = "";
		$defaultImage = "product_placeholder.png";

		if (isset($this->media) && !empty($this->media["photos"])) {
			// Try to find the "main_product_photo"
			foreach ($this->media["photos"] as $key => $photo) {
				if (isset($photo["main_product_photo"]) && $photo["main_product_photo"]) {

					// Try to get the cropped photo
					if (isset($photo['name_cropped'])) {
						$image = $photo["name_cropped"];
					} else {
						$image = $photo["name"];
					}

					break;
				}
			}

			if (!$image) {
				// If there is no image... we use the first image
				$image = $this->media["photos"][0]["name"];
			}

			if ($image && $this->existMediaFile($image)) {

				// Only use the image if it exists...
				$image = Yii::getAlias("@product_url") . "/" . $this->short_id . "/" . $image;

				return $image;
			}
		}

		// Default image
		$image = $defaultImage;
		$image = Yii::getAlias("@web") . "/imgs/" . $image;

		return $image;
	}

	/**
	 * Returns the url to view the product
	 *
	 * @return string
	 */
	public function getViewLink() {
		return Url::to(["/product/detail", "slug" => $this->getSlug(), 'product_id' => $this->short_id], true);
	}

	/**
	 * Returns the url to edit the product
	 *
	 * @return string
	 */
	public function getEditLink() {
		$deviser = $this->getDeviser();
		return Url::to(['/product/edit', 'slug' => $deviser->getSlug(), 'product_id' => $this->short_id, 'person_id' => $this->deviser_id,
			"person_type" => $deviser->getPersonTypeForUrl()], true);
	}

	public function getSlug() {
		if (is_array($this->slug)) {
			$slug = Utils::l($this->slug);
			if (empty($slug) && isset($this->slug[Lang::EN_US])) {
				$slug = $this->slug[Lang::EN_US];
			}
		} else {
			$slug = $this->slug;
		}
		return $slug;
	}

	public function getName() {
		if (is_array($this->name)) {
			$name = Utils::l($this->name);
			if (empty($name) && isset($this->name[Lang::EN_US])) {
				$name = $this->name[Lang::EN_US];
			}
		} else {
			$name = $this->name;
		}
		return $name;
	}

	/**
	 * Get a resized version of main image, to 128px width
	 *
	 * @return string
	 */
	public function getImagePreview($width, $height, $fill = null)
	{
		$image = $this->getMainImage();

		if ($fill) {
			$url = Utils::url_scheme() . Utils::thumborize($image)->fitIn($width, $height)->addFilter('fill', $fill);
		} else {
			$url = Utils::url_scheme() . Utils::thumborize($image)->resize($width, $height);
		}

		return $url;
	}

	/**
	 * Wrapper to serialize fields
	 *
	 * @return string
	 */
	public function getImagePreview128()
	{
		return $this->getImagePreview(128, 0);
	}

	/**
	 * Wrapper to serialize fields
	 *
	 * @return string
	 */
	public function getImagePreview256()
	{
		return $this->getImagePreview(256, 0);
	}

	/**
	 * Wrapper to serialize fields
	 *
	 * @return string
	 */
	public function getImagePreview512()
	{
		return $this->getImagePreview(512, 0);
	}

	/**
	 * Wrapper to serialize fields
	 *
	 * @return string
	 */
	public function getImagePreview256Fill()
	{
		return $this->getImagePreview(256, 256, 'white');
	}

	/**
	 * Get videos related with this Product
	 *
	 * @return array
	 */
	public function getVideos()
	{
		$videos = [];

		$persons = Person::find()->where(["videos.products" => $this->short_id])->all();
		/** @var Person $person */
		foreach ($persons as $person) {
			/** @var PersonVideo $video */
			foreach ($person->findVideosByProductId($this->short_id) as $video) {
				$videos[$video->url] = $video;
			}
		}

		// Add all deviser videos
		$deviser = Person::findOneSerialized($this->deviser_id);
		$deviserVideos = $deviser->videosMapping;
		foreach ($deviserVideos as $oneVideo) {
			$videos[$oneVideo->url] = $oneVideo;
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
	 * Note that "main_product_photo" is returned in the first position of the array
	 *
	 * @return array
	 */
	public function getUrlGalleryImages()
	{
		$images = [];
		if (array_key_exists("photos", $this->media)) {
			foreach ($this->media["photos"] as $imageData) {
				if (isset($imageData['main_product_photo']) && $imageData['main_product_photo']) {
					$mainPhoto = Yii::getAlias('@product_url') . '/' . $this->short_id . '/' . $imageData['name'];
				} else {
					$images[] = Yii::getAlias('@product_url') . '/' . $this->short_id . '/' . $imageData['name'];
				}
			}
		}
		if (isset($mainPhoto)) {
			array_unshift($images, $mainPhoto);
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
	 * Returns the deviser owner of the product
	 *
	 * @return Person
	 */
	public function getDeviser()
	{
		return Person::findOneSerialized($this->deviser_id);
	}

	/**
	 * Get a preview version of a Deviser
	 *
	 * @return array
	 */
	public function getDeviserPreview()
	{
		/** @var Person $deviser */
		$deviser = $this->getDeviser();
		return $deviser->getPreviewSerialized();
	}

	/**
	 * Wrapper of isLovedByCurrentUser to use in serialized fields
	 *
	 * @return bool
	 */
	public function getIsLoved() {
		return $this->isLovedByCurrentUser();
	}

	/**
	 * Wrapper of isWorkFromCurrentUser to use in serialized fields
	 *
	 * @return bool
	 */
	public function getIsMine() {
		return $this->isWorkFromCurrentUser();
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
	 * Get label to show returns conditions
	 *
	 * @return string
	 */
	public function getReturnsLabel()
	{
		$label = '';
		if (!empty($this->returns)) {
			$warrantyType = $this->returns["type"];
			if (($warrantyType != Returns::NONE) && (array_key_exists("value", $this->returns))) {
				$label .= $this->returns["value"] . ' ';
			}
			$label .= Returns::getDescription($this->returns["type"]);
		}
		return $label;
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

		if (array_key_exists('media', $data)) {
			$this->mediaMapping->load($data, 'media');
		}

		if (array_key_exists('preorder', $data)) {
			$this->preorderMapping->load($data, 'preorder');
		}

		if (array_key_exists('madetoorder', $data)) {
			$this->madeToOrderMapping->load($data, 'madetoorder');
		}

		if (array_key_exists('bespoke', $data)) {
			$this->bespokeMapping->load($data, 'madetoorder');
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
			'id' => $this->short_id,
			'slug' => $this->slug,
			'name' => $this->name,
			'media' => $this->media,
			'deviser' => $this->getDeviserPreview(),
			'main_photo' => $this->getMainImage(),
			'main_photo_128' => $this->getImagePreview128(),
			'main_photo_256' => $this->getImagePreview256(),
			'main_photo_512' => $this->getImagePreview512(),
			'main_photo_256_fill' => $this->getImagePreview256Fill(),
			'url_images' => $this->getUrlImagesLocation(),
			'link' => $this->getViewLink(),
			'edit_link' => $this->getEditLink(),
			'isLoved' => $this->getIsLoved(),
			'isMine' => $this->getIsMine(),
			'min_price' => $this->getMinimumPrice(),
		];
	}

	/**
	 * Add additional error to make easy show labels in client side
	 */
	public function afterValidate()
	{
		parent::afterValidate();
		foreach ($this->errors as $attribute => $error) {
			switch ($attribute) {
				default:
					if (Utils::isRequiredError($error)) {
						$this->addError("required", $attribute);
					}
					$this->addError("fields", $attribute);
					break;
			}
		};
	}

	public function getTempUploadedFilesPath()
	{
		return Utils::join_paths(Yii::getAlias("@product"), "temp");

	}

	public function getUploadedFilesPath()
	{
		return Utils::join_paths(Yii::getAlias("@product"), $this->short_id);
	}

	/**
	 * Check if Deviser media file is exists
	 *
	 * @param string $filename
	 * @return bool
	 */
	public function existMediaFile($filename)
	{
		if (empty($filename)) {
			return false;
		}

		$filePath = Utils::join_paths($this->getUploadedFilesPath(), $filename);

		return file_exists($filePath);
	}

	/**
	 * Check if Deviser media file is exists
	 *
	 * @param string $filename
	 * @return bool
	 */
	public function existMediaTempFile($filename)
	{
		if (empty($filename)) {
			return false;
		}

		$filePath = Utils::join_paths($this->getTempUploadedFilesPath(), $filename);

		return file_exists($filePath);
	}

	/**
	 * Moves all temporary uploads to definitive product's path
	 */
	public function moveTempUploadsToProductPath()
	{
		foreach ($this->media['photos'] as $onephoto) {
			$this->moveTempFileToProductPath($onephoto['name']);
			if (isset($onephoto['name_cropped'])) {
				$this->moveTempFileToProductPath($onephoto['name_cropped']);
			}
		}
		foreach ($this->media['description_photos'] as $onephoto) {
			$this->moveTempFileToProductPath($onephoto['name']);
			if (isset($onephoto['name_cropped'])) {
				$this->moveTempFileToProductPath($onephoto['name_cropped']);
			}
		}
	}

	protected function moveTempFileToProductPath($file)
	{
		$tempFile = Utils::join_paths($this->getTempUploadedFilesPath(), $file);
		if (!file_exists($tempFile)) {
			return;
		}
		$path_destination = $this->getUploadedFilesPath();
		$destination = Utils::join_paths($path_destination, $file);

		if (!file_exists($destination)) {
			if (!file_exists($path_destination)) {
				FileHelper::createDirectory($path_destination);
			}
			rename($tempFile, $destination);
		}
	}

	/**
	 * Returns a number of random works.
	 *
	 * @param int $limit
	 * @param array $categories
	 * @return Product[]
	 */
	public static function getRandomWorks($limit, $categories = [])
	{
		// Exclude drafts
		$conditions[] =
				[
						'$match' => [
								"product_state" => [
										'$eq' => Product::PRODUCT_STATE_ACTIVE,
								]
						]
				];

		// Filter by category if present
		if (!empty($categories)) {
			$conditions[] =
					[
							'$match' => [
									"categories" => [
											'$in' => $categories
									]
							]
					];
		}

		// Randomize
		$conditions[] =
				[
						'$sample' => [
								'size' => $limit,
						]
				];

		$randomWorks = Yii::$app->mongodb->getCollection('product')->aggregate($conditions);

		$workIds = [];
		foreach ($randomWorks as $work) {
			$workIds[] = $work['_id'];
		}

		if ($workIds) {
			$query = new ActiveQuery(Product::className());
			$query->where(['in', '_id', $workIds]);
			$works = $query->all();
			shuffle($works);
		} else {
			$works = [];
		}

		return $works;
	}

	public function getPriceStockItem($priceStockId) {
		$priceStock = $this->price_stock;
		foreach ($priceStock as $item) {
			if ($item['short_id'] == $priceStockId) {
				return $item;
			}
		}
		return null;
	}

	/**
	 * Returns TRUE if the product is from the connected user
	 *
	 * @return bool
	 */
	public function isWorkFromCurrentUser() {
		if(Yii::$app->user->isGuest) {
			return false;
		}
		$person_id = Yii::$app->user->identity->short_id;
		return $this->deviser_id == $person_id;
	}

	/**
	 * Returns TRUE if the product is loved by the connected user
	 *
	 * @return bool
	 */
	public function isLovedByCurrentUser() {
		if (Yii::$app->user->isGuest) {
			return false;
		}
		$person_id = Yii::$app->user->identity->short_id;

		return Utils::productLovedByPerson($this->short_id, $person_id);
	}

	/**
	 * Returns TRUE if the product is in a box of the connected user
	 *
	 * @return bool
	 */
	public function isInBoxOfCurrentUser() {
		if (Yii::$app->user->isGuest) {
			return false;
		}
		$person_id = Yii::$app->user->identity->short_id;

		$boxes = Box::findSerialized(
			[
				'person_id' => $person_id,
				'product_id' => $this->short_id,
			]
		);

		return !empty($boxes);
	}

	/**
	 * Returns Loveds from the product
	 *
	 * @return Loved[]
	 */
	public function getLoveds() {
		return Loved::findSerialized(['product_id' => $this->short_id]);
	}

	/**
	 * Returns boxes that include this product
	 *
	 * @return Box[]
	 */
	public function getBoxes() {
		return Box::findSerialized(['product_id' => $this->short_id]);
	}

	/**
	 * Returns orders with this product
	 *
	 * @return Order[]
	 */
	public function getOrders() {
		return Order::findSerialized(['product_id' => $this->short_id]);
	}

	/**
	 * Returns TRUE if the product has any order (order paid, ignoring carts)
	 *
	 * @return bool
	 */
	public function hasOrders() {
		$orders = $this->getOrders();
		foreach ($orders as $order) {
			if ($order->isOrder()) {
				return true;
			}
		}

		return false;
	}

	/**
	 * Returns the shipping price of an price stock item to a country
	 * If no priceStockId is specified, returns shipping price for the first variation available
	 * If no countryCode is specified, returns shipping price for the default country
	 * If there is no shipping price defined for the parameters, returns null
	 *
	 * @param string $priceStockId
	 * @param string $countryCode
	 *
	 * @return double|null
	 */
	public function getShippingPrice($priceStockId = null, $countryCode = null)
	{
		if (empty($countryCode)) {
			$countryCode = Country::getDefaultContryCode();
		}
		$deviser = $this->getDeviser();
		$priceStocks = $this->price_stock;
		foreach ($priceStocks as $priceStock) {
			if ($priceStock['available'] && (empty($priceStockId) || $priceStock['short_id'] == $priceStockId)) {
				$shippingSettingRange = $deviser->getShippinSettingRange($priceStock['weight'], $countryCode);

				return $shippingSettingRange['price'];
			}
		}

		return null;
	}
}