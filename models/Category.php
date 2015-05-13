<?php
namespace app\models;

use Yii;
use yii\base\Model;
use app\helpers\CActiveRecord;
use yii\mongodb\Query;

class CategoryName extends Model {
	private $dynamicFields;

	public function __construct() {
		$this->dynamicFields = array_flip(array_keys(Yii::$app->languagepicker->languages));
	}

	public function __set($name, $value) {
		if (array_key_exists($name, $this->dynamicFields)) {
			$this->dynamicFields[$name] = $value;
		} else {
			parent::__set($name, $value);
		}
	}

	public function __get($name) {
		if (array_key_exists($name, $this->dynamicFields)) {
			return $this->dynamicFields[$name];
		} else {
			return parent::__get($name);
		}
	}

}

class Category extends CActiveRecord {

	public function embedNameModel() {
		return $this->mapEmbedded("name", CategoryName::className());
	}

	public static function collectionName() {
		return 'category';
	}

	public function attributes() {
		return [
			'_id',
			'short_id',
			'path',
			'name',
			'slug'
		];
	}

	public function getSubCategories() {
		$current_path = $this->path . $this->short_id . "/";

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

	public function move($new_path) {
		$current_path = $this->path . $this->short_id . "/";
		$new_sub_path = $new_path . $this->short_id . "/";

		foreach($this->getSubCategories() as $category) {
			//TODO: Optimize with an update instead of find + save?
			$category = Category::findOne(['short_id' => $category["short_id"]]);
			$category->path = str_replace($current_path, $new_sub_path, $category->path, $count = 1);
			$category->save();
		}

		$this->path = $new_path;
		$this->save();
	}

	public function remove() {
		foreach($this->getSubCategories() as $category) {
			$category = Category::findOne(["short_id" => $category["short_id"]]);
			//TODO: Find all products and remove this category from each one
			//TODO: Notify the product's deviser that this product was removed from the category we're about to delete
			$category->delete();
		}

		$this->delete();
	}
}