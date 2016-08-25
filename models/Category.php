<?php
namespace app\models;

use app\helpers\Utils;
use Yii;
use app\helpers\CActiveRecord;
use yii\mongodb\Query;

/**
 * @property string path
 * @property bool sizecharts
 * @property bool prints
 * @property mixed|string name
 * @property string slug
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
			'slug'
		];
	}

    /**
     * The attributes that should be translated
     *
     * @var array
     */
    public $translatedAttributes = ['name'];


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
                    'path',
                    'sizecharts',
                    'prints',
                    'name',
                    'slug',
                ];
                static::$translateFields = true;
                break;
            case CActiveRecord::SERIALIZE_SCENARIO_ADMIN:
                static::$serializeFields = [
                    'id' => 'short_id',
                    'path',
                    'sizecharts',
                    'prints',
                    'name',
                    'slug',
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
     * @return array
     */
    public static function getSerialized() {

        // retrieve only fields that want to be serialized
        $categories = Category::find()->select(array_values(static::$serializeFields))->all();

        // if automatic translation is enabled
        if (static::$translateFields) {
            Utils::translate($categories);
        }
        return $categories;
    }

	/**
	 * Get list of categories to use in header menu
	 *
	 * @return array
	 */
	public static function getHeaderCategories()
	{
		// TODO Forced for the demo. This must be selected in "admin" panel.
		return Category::find()->where(["short_id" => ['1a23b', '4a2b4', '3f78g']])->all();
	}

	/**
	 * Get list of categories to use in footer menu
	 *
	 * @return array
	 */
	public static function getFooterCategories()
	{
		// TODO Forced for the demo. This must be selected in "admin" panel.
		return Category::find()->where(["short_id" => ['1a23b', '2r67s', '4a2b4']])->all();
	}

	public function getSubCategories($current_path = null) {
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
			$subs = $this->getSubCategories($current_path);
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
		return $this->deviserSubcategories;
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

				foreach($this->getSubCategories($current_path) as $category) {
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
		foreach($this->getSubCategories() as $category) {
			$category = Category::findOne(["short_id" => $category["short_id"]]);
			//TODO: Find all products and remove this category from each one
			//TODO: Notify the product's deviser that this product was removed from the category we're about to delete
			$category->delete();
		}

		return parent::beforeDelete();
	}



}