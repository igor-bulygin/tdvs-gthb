<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use EasySlugger\Slugger;
use Yii;
use yii\helpers\Url;
use yii\mongodb\ActiveQuery;

/**
 * @property string path
 * @property bool sizecharts
 * @property bool prints
 * @property mixed|string name
 * @property string slug
 * @property int header_position
 * @property array header_products
 */
class Category extends CActiveRecord {

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


	/** @var Product */
	private $deviserProduct;

	/** @var array */
	private $deviserSubcategories;


	public static function collectionName() {
		return 'category';
	}


	public function attributes() {
		return [
			'_id',
			'short_id',
			'path',
			'sizecharts',
			'prints',
			'name',
			'slug',
			'header_position',
			'header_products',
		];
	}

    /**
     * The attributes that should be translated
     *
     * @var array
     */
    public static $translatedAttributes = ['name', 'slug'];

	/**
	 * Initialize model attributes
	 */
	public function init()
	{
		parent::init();
		$this->deviserProduct = [];
	}

	public function rules()
	{
		return [
			[
				[
					'short_id',
					'path',
					'sizecharts',
					'prints',
					'name',
					'slug',
					'header_position',
					'header_products',
				],
				'safe',
			],
			[
				'name',
				'app\validators\TranslatableValidator',
			],
			[
				'header_position',
				'integer',
			]
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
            case self::SERIALIZE_SCENARIO_PUBLIC:
                static::$serializeFields = [
                    'id' => 'short_id',
					'short_id',
                    'path',
                    'sizecharts',
                    'prints',
                    'name',
                    'slug',
                    'header_position',
                    'header_products',
                ];
                static::$translateFields = true;
                break;
            case self::SERIALIZE_SCENARIO_ADMIN:
                static::$serializeFields = [
                    'id' => 'short_id',
					'short_id',
                    'path',
                    'sizecharts',
                    'prints',
                    'name',
                    'slug',
					'header_position',
					'header_products',
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
     * Get a collection of entities serialized, according to serialization configuration
     *
     * @param array $criteria
     * @return Category[]
     */
    public static function findSerialized($criteria = [])
    {

	    $query = new ActiveQuery(Category::className());

	    // Retrieve only fields that gonna be used
	    $query->select(self::getSelectFields());

		// if category id is specified
		if ((array_key_exists("id", $criteria)) && (!empty($criteria["id"]))) {
			$query->andWhere(["short_id" => $criteria["id"]]);
		}

	    // only root nodes, or all
	    if (array_key_exists("scope", $criteria)) {
			switch ($criteria["scope"]) {
				case "all":
					break;
				case "roots":
				default:
					$query->andWhere(["path" => "/"]);
					break;
			}
	    }

	    // Count how many items are with those conditions, before limit them for pagination
	    static::$countItemsFound = $query->count();


	    // limit
	    if (array_key_exists("limit", $criteria) && !empty($criteria["limit"])) {
		    $query->limit($criteria["limit"]);
	    }

	    // offset for pagination
	    if (array_key_exists("offset", $criteria) && !empty($criteria["offset"])) {
		    $query->offset($criteria["offset"]);
	    }

		if ((array_key_exists("order_col", $criteria)) && (!empty($criteria["order_col"])) &&
			(array_key_exists("order_dir", $criteria)) && (!empty($criteria["order_dir"]))) {
			$query->orderBy([
				$criteria["order_col"] => $criteria["order_dir"] == 'desc' ? SORT_DESC : SORT_ASC,
			]);
		} else {
			$query->orderBy([
				"created_at" => SORT_DESC,
			]);
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
	 * @return Category|null
	 */
	public static function findOneSerialized($id)
	{
		/** @var Category $category */
		$category = Category::find()->select(self::getSelectFields())->where(["short_id" => $id])->one();

		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($category);
		}

		return $category;
	}

	/**
	 * Get list of categories to use in header menu
	 *
	 * @return array
	 */
	public static function getHeaderCategories()
	{
		$query = Category::find()
			->andWhere(["path" => "/"])
			->andWhere([">", "header_position", 0])
			->orderBy([
				'header_position' => SORT_ASC,
			]
		);

		$items = $query->all();


		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($items);
		}
		return $items;
	}

	/**
	 * Get list of categories to use in footer menu
	 *
	 * @return array
	 */
	public static function getFooterCategories()
	{
		return static::getHeaderCategories();
	}

	/**
	 * Returns subcategories of current category that are configured to be shown in header menu
	 *
	 * @return Category[]
	 */
	public function getSubCategoriesHeader()
	{
		$current_path = $this->path . $this->short_id . "/";

		$query = Category::find()
			->andWhere(["path" => $current_path])
			->andWhere([">", "header_position", 0])
			->orderBy([
					'header_position' => SORT_ASC,
				]
			);

		$items = $query->all();


		// if automatic translation is enabled
		if (static::$translateFields) {
			Utils::translate($items);
		}

		return $items;
	}

	/**
	 * Returns the products to use in shop by deparment header.
	 *
	 * This method tries to get products defined in "header_products" property of the category
	 * If there is no products in this property, the method tries to get $limit random products in the category
	 *
	 * @param int $limit
	 * @return Product[]
	 */
	public function getHeaderProducts($limit = 100)
	{
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);

		if ($this->header_products) {
			$products = Product::findSerialized([
				'id' => $this->header_products,
				'product_state' => Product::PRODUCT_STATE_ACTIVE,
			]);
		}

		if (empty($products)) {

			// if there is no hardcoded products, we get some random products of the category

			$products = Product::findSerialized(
				[
					'categories' => [$this->short_id],
					'product_state' => Product::PRODUCT_STATE_ACTIVE,
					'limit' => $limit,
				]
			);

			if (count($products) > 3) {
				$products = array_slice($products, rand(0, count($products)), 3);
			}
		}

		return $products;
	}


	/**
	 * Returns an array with the images to use in shop by deparment header.
	 *
	 * The images are fixed for a group of categories (files in /imgs/category_{%category_slug%}_{g|s|s1}.jpg
	 * If the images not exists in filesystem, this method try to get the "header products", and use them as image/link
	 *
	 * Array has the next keys:
	 * - url => Url to the image (relative to base_url)
	 * - link => (optional) Link when the image is clicked
	 * - name => (optional) to use as title attribute in the <a> href tag
	 *
	 * @return array
	 */
	public function getHeaderImages()
	{
		$names = [
			[
				'url' => "/imgs/category_" . strtolower($this->getFileName()) . "_g.jpg",
				'link' => null,
			],
			[
				'url' => "/imgs/category_" . strtolower($this->getFileName()) . "_s.jpg",
				'link' => null,
			],
			[
				'url' => "/imgs/category_" . strtolower($this->getFileName()) . "_s1.jpg",
				'link' => null,
			],
		];
		foreach ($names as $k => $image) {
			if (!file_exists(Yii::getAlias('@webroot').$image['url'])) {
				$names = [];
				$headerProducts = $this->getHeaderProducts(3);
				foreach ($headerProducts as $product) {
					$names[] = [
						'url' => Utils::url_scheme() . Utils::thumborize($product->getMainImage())->resize(398, 235),
						'link' => $product->getViewLink(),
						'name' => $product->name,
					];
				}

				return $names;
			}
		}

		return $names;
	}

	/**
	 * @return Category[]
	 */
	public function getSubCategories($recursive = false) {
		return static::findByPath($this->path . $this->short_id . "/", $recursive);
	}

	/**
	 * @return Category|null
	 */
	public function getParentCategory() {
		if ($this->path == '/') {
			return null;
		}

		$ancestors = explode('/', rtrim(ltrim($this->path, '/'), '/'));
		if ($ancestors) {
			$parentId = $ancestors[count($ancestors) - 1];
			$parent = static::findOne(['short_id' => $parentId]);
			if ($parent) {
				return $parent;
			}
		}

		return null;
	}

	public static function findByPath($path, $recursive = false) {

		if ($recursive) {
			// Escape slashes
			$path = str_replace('/', '\/', $path);
			return Category::find()
				->andWhere(["REGEX", "path", "/$path/"])
				->all();
		}

		return Category::find()->andWhere(["path" => $path])->all();
	}

	/**
	 * Get short_id from current category, and optionally, child categories.
	 *
	 * @param bool $includeChild
	 * @return array
	 */
	public function getShortIds($includeChild = true) {

		$ids = [$this->short_id];
		if ($includeChild) {
			$subs = $this->getSubCategories(true);
			foreach ($subs as $sub) {
				$ids[] = $sub->short_id;
			}
		}
		return $ids;
	}

	/**
	 * @return Product
	 */
	public function getDeviserProduct()
	{
		return $this->deviserProduct;
	}

	/**
	 * @param Product $deviserProduct
	 */
	public function setDeviserProduct($deviserProduct)
	{
		$this->deviserProduct = $deviserProduct;
	}


	/**
	 * @return array
	 */
	public function getDeviserSubcategories()
	{
		return empty($this->deviserSubcategories) ? [] : $this->deviserSubcategories;
	}

	/**
	 * @param array $deviserSubcategories
	 */
	public function setDeviserSubcategories($deviserSubcategories)
	{
		$this->deviserSubcategories = $deviserSubcategories;
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

		$slugs = [];
		foreach (Lang::getAvailableLanguages() as $lang => $name) {
			if (isset($this->name[$lang])) {
				$slugs[$lang] = Slugger::slugify($this->name[$lang]);
			} else {
				$slugs[$lang] = Slugger::slugify($this->name[Lang::EN_US]);
			}
		}
		$this->setAttribute("slug", $slugs);

		if (empty($this->header_position)) {
			$this->header_position = null;
		}

		if($insert) {
			//insert
		} else {
			//update
			$dirty = $this->getDirtyAttributes();
			$dirty_values = $this->getOldAttributes();

			/**
			 * We must check if the path of the category changed, and if so, move all sub-categories to the
			 * new (sub)path.
			 */
			if(array_key_exists("path", $dirty) && array_key_exists("path", $dirty_values)) {

				$current_path = $dirty_values["path"] . $this->short_id . "/";
				$new_sub_path = $this->path . $this->short_id . "/";

				$categories = Category::findByPath($current_path, true);
				foreach($categories as $category) {
					//TODO: Optimize with an update instead of find + save?
					$category = Category::findOne(['short_id' => $category["short_id"]]);
					$category->path = str_replace($current_path, $new_sub_path, $category->path, $count = 1);
					$category->save();
				}
			}
		}

		return parent::beforeSave($insert);
	}

	public function beforeDelete() {
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_OWNER);

		// Delete subcategories first
		$subCategories = $this->getSubCategories(true);
		foreach($subCategories as $subCategory) {
			$subCategory->delete();
		}

		// Find all products and remove this category from each one
		$products = Product::findSerialized(
			[
				'categories' => $this->short_id,
			]
		);

		foreach ($products as $product) {
			$categories = $product->categories;
			if (($key = array_search($this->short_id, $categories)) !== false) {
				unset($categories[$key]);
				$product->categories = $categories;
				$product->save();
			}

			//TODO: Notify the product's deviser that this product was removed from the category we're about to delete
		}

		return parent::beforeDelete();
	}

//	public function behaviors()
//	{
//		return array_merge(
//			parent::behaviors(),
//			[
//				'positionBehavior' => [
//					'class' => PositionBehavior::className(),
//					'positionAttribute' => 'header_position',
//					'groupAttributes' => [
//						'path' // multiple lists varying by 'path'
//					],
//				],
//			]
//		);
//	}

	/**
	 * Returns the main category (first level category) of the current object
	 *
	 * @return Category
	 */
	public function getMainCategory()
	{
		if ($this->path == '/') {
			return $this;
		}
		$paths = array_filter(explode('/', $this->path));
		$category = Category::findOne(['short_id' => reset($paths)]);
		return $category;
	}

	/**
	 * Returns the path to de image to be shown on the main banner of the home page
	 * @return string
	 */
	public function getBannerImage()
	{
		$fileName = "/imgs/banner-" . strtolower($this->getFileName()) . ".jpg";
		if (file_exists(Yii::getAlias('@webroot') . $fileName)) {
			return $fileName;
		}
		return null;
	}

	/**
	 * Returns the path to de image to be shown on the "shop by deparment" section of the header
	 * @return string
	 */
	public function getHeaderImage()
	{
		$fileName = "/imgs/mini-banner-" . strtolower($this->getFileName()) . ".jpg";
		if (file_exists(Yii::getAlias('@webroot') . $fileName)) {
			return $fileName;
		}
		return null;
	}

	/**
	 * Returns TRUE if the current category has groups of categories to be shown on the "shop by deparment" header
	 *
	 * @return bool
	 */
	public function hasGroupsOfCategories() {

		$current_path = $this->path . $this->short_id . "/";

		// Escape slashes
		$current_path = str_replace('/', '\/', $current_path);

		// example of regexp for short_id 4a2b4 (note last slash before $, to get only first level childs):
		//				     /\/4a2b4\/\w{5}\/$/

		$query = Category::find()
			->andWhere(["REGEX", "path", "/$current_path\w{5}\/$/"])
			->andWhere([">", "header_position", 0])
			->orderBy([
					'header_position' => SORT_ASC,
				]
			);

		$items = $query->all();

		return !empty($items);
	}

	public function getSlug()
	{
		if (is_array($this->slug)) {
			$slug = Utils::l($this->slug);
		} else {
			$slug = $this->slug;
		}

		return $slug;
	}

	public function getFileName()
	{
		if (is_array($this->slug)) {
			// if we have a "no translated" object, we get US translation
			return $this->slug[Lang::EN_US];
		}

		// otherwise, we need to find the object again
		$category = static::findOne(['short_id' => $this->short_id]);

		return $category->slug[Lang::EN_US];
	}


	public function getName()
	{
		if (is_array($this->name)) {
			$name = Utils::l($this->name);
		} else {
			$name = $this->name;
		}

		return $name;
	}

	public function getSlugForUrl()
	{
		$parent = $this->getParentCategory();
		$prefix = $parent ? $parent->getSlugForUrl().'-' : '';

		return $prefix.Slugger::slugify($this->getName());
	}

	public function getMainLink()
	{
		return Url::to([
			"public/category-b",
			"slug" => $this->getSlugForUrl(),
			'category_id' => $this->short_id
		]);
	}
}