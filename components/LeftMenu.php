<?php

namespace app\components;

use Yii;
use yii\base\Widget;
use app\helpers\Utils;
use app\models\Category;

class LeftMenu extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		$lang = Yii::$app->language;
		$categories = Category::find()
			//->where(["path" => "/"])
			->orderBy(['name.' . $lang => SORT_ASC])
			->asArray()
			->all();

		$nested_categories = [];
		foreach ($categories as $i => $category) {
			if ($category['path'] === "/") {
				$nested_categories[] = $category;
			} else {
				$path_arr = explode("/", $category['path']);

				$parent_category = $path_arr[count($path_arr) - 2];
				!array_key_exists($parent_category, $nested_categories) && $nested_categories[$parent_category] = [];
				$nested_categories[$parent_category][] = $category;
			}
		}

		return $this->render('LeftMenu', [
			'lang' => $lang,
			'categories' => $nested_categories
		]);
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views', 'LeftMenu', Yii::getAlias('@device'));
	}
}
