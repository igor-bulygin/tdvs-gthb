<?php
namespace app\models;

use app\helpers\CActiveRecord;
use app\helpers\Utils;
use Yii;
use yii\helpers\Url;
use yii\mongodb\ActiveQuery;
use yii\mongodb\Query;

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
    public static $translatedAttributes = ['name'];

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
                    'path',
                    'sizecharts',
                    'prints',
                    'name',
                    'slug',
                    'header_products',
                ];
                static::$translateFields = true;
                break;
            case self::SERIALIZE_SCENARIO_ADMIN:
                static::$serializeFields = [
                    'id' => 'short_id',
                    'path',
                    'sizecharts',
                    'prints',
                    'name',
                    'slug',
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
     * @return array
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
				case "header":
					$query->andWhere([">", "header_position", 0]);
					$query->andWhere(["path" => "/"]);
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
		$categoriesIds = [
			'1a23b', // art
			'4a2b4', // fashion
			// beauty
			'f0cco', // technology
			'ca82k', // sports
			'2r67s', // interior design
			'3f78g', // jewely
		];

		Category::setSerializeScenario(Category::SERIALIZE_SCENARIO_PUBLIC);
		$categories = Category::findSerialized(
			[
				'scope' => 'header',
				'order_col' => 'header_position',
				'order_dir' => SORT_ASC,
			]
		);

		return $categories;
	}

	/**
	 * Get list of categories to use in footer menu
	 *
	 * @return array
	 */
	public static function getFooterCategories()
	{
		// TODO Forced for the demo. This must be selected in "admin" panel.
		$categories = Category::find()->where(["path" => "/"])->all();
		foreach ($categories as $k => $category) {
			if ($category->short_id == 'ffeec') { // More must be the last one
				unset($categories[$k]);
				$categories[$k] = $category;
			}
		}
		return $categories;
	}

	/**
	 * Returns the products to show in header menu
	 *
	 * @param int $limit
	 * @return Product[]
	 */
	public function getHeaderProducts($limit)
	{
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);

		if ($this->header_products) {
			$products = Product::findSerialized([
				'id' => $this->header_products,
			]);

		} else {

			// if there is no hardcoded products, we get randome products of the category

			$products = Product::findSerialized(
				[
					'categories' => [$this->short_id],
					'limit' => 100,
				]
			);

			if (count($products) > 3) {
				$products = array_slice($products, rand(0, count($products)), 3);
			}
		}

		return $products;
	}

	/**
	 * @return Category[]
	 */
	public function getSubCategoriesHeader() {

		/*
		FASHION - WOMENSWEAR - Accessories, Coats & Jackets, Dresses, Footwear, Jeans, Knitwear, Tops

		FASHION - MENSWEAR - Accessories, Coats & Jackets, Footwear, Jeans, Shirts, Suits & Blazers, T-shirts

		DECORATION: Carpets, Furniture, Home Accessories, Lighting, Tableware

		ART: Ceramic, Painting, Photography, Printmaking, Sculpture

		JEWELRY: Bracelets, Collars, Earrings, Necklaces, Rings, Watches

		TECNOLOGY: sin subcategorías (si pinchamos en GADGETS nos lleva a la home de GADGETS)

		FOOD & BEVERAGE: sin subcategorías (si pinchamos en FOOD & BEVERAGES nos lleva a la home de FOOD & BEVERAGE)

		MORE que salgan las 3 categorías que hay actualmente si es posible: automotive, living, musical instruments

		*/

		$fixedCategories = [
			// Womenswear
			'bf73v',
			'22ecr',
			'd9aaa',
			'e7d15',
			'4c1d2',
			'3ac6t',
			'5d2ek',

			// Menswear
			'9e5e6',
			'ada11',
			'2029g',
			'ab0a7',
			'8d1a2',
			'b5144',
			'8c303',

			// Decoration
			'2b11c',
			'2a10b',
			'4f2a0',
			'2237e',
			'7707g',

			// Art
			'1b34c',
			'1h10i',
			'1i11j',
			'1j12k',
			'1k13l',

			// jewelry
			'ef4ch',
			'9a7bu',
			'3abc9',
			'3klm5',
			'3145q',
			'3lva9',

			// more
			'663d4',
			'7642n',
			'76de8',

		];
		$current_path =$this->path . $this->short_id . "/";
		return Category::find()
				->where(["REGEX", "path", "/^$current_path/"])
				->andWhere(['in', 'short_id', $fixedCategories])
				->all();
	}

	/**
	 * @return Category[]
	 */
	public function getSubCategories() {
		return Category::find()->where(["path" => $this->path . $this->short_id . "/"])->all();
	}

	public function getSubCategoriesOld($current_path = null) {
		if ($current_path === null) {
			$current_path = $this->path . $this->short_id . "/";
		}

		/* @var $db \MongoCollection */
		//$db = Yii::$app->mongodb->getCollection(["todevise", "category"]);
		//$cursor = $db->find([
		//	"path" => (new \MongoRegex("/^$current_path/"))
		//]);
		//$subcategories = [];
		//while ($cursor->hasNext()) {
		//	$subcategories[] = $cursor->getNext();
		//}
		//return $subcategories;

		return (new Query)->
			select([])->
			from('category')->
			where(["REGEX", "path", "/^$current_path/"])->all();
	}

	/**
	 * Get short_id from current category, and optionally, child categories.
	 *
	 * @param null $current_path
	 * @param bool $includeChild
	 * @return array
	 */
	public function getShortIds($current_path = null, $includeChild = true) {

		$ids = [$this->short_id];
		if ($includeChild) {
			$subs = $this->getSubCategoriesOld($current_path);
			foreach ($subs as $sub) {
				$ids[] = $sub["short_id"];
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
		/*
		 * Create empty data holders if they don't exist
		 */
		if($this->name == null) {
			$this["name"] = [];
		}

		if($this->slug == null) {
			$this["slug"] = [];
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

				foreach($this->getSubCategoriesOld($current_path) as $category) {
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
		foreach($this->getSubCategoriesOld() as $category) {
			$category = Category::findOne(["short_id" => $category["short_id"]]);
			//TODO: Find all products and remove this category from each one
			//TODO: Notify the product's deviser that this product was removed from the category we're about to delete
			$category->delete();
		}

		return parent::beforeDelete();
	}

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
		$fileName = "/imgs/banner-" . strtolower($this->slug) . ".jpg";
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
		$fileName = "/imgs/mini-banner-" . strtolower($this->slug) . ".jpg";
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
		return $this->short_id == '4a2b4'; // at this moment only fashion has this behaviour
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

	public function getMainLink()
	{
		return Url::to([
			"public/category-b",
			"slug" => $this->getSlug(),
			'category_id' => $this->short_id
		]);
	}
}