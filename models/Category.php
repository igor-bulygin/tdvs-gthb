<?php
namespace app\models;

use Yii;
use app\helpers\CActiveRecord;
use yii\mongodb\Query;

class Category extends CActiveRecord {

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