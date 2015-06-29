<?php

namespace app\components;

use Yii;
use yii\base\Widget;
use app\models\Category;

class CategoriesNavbar extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		$lang = Yii::$app->language;
		$categories = Category::find()
			->where(["path" => "/"])
			->orderBy(['name.' . $lang => SORT_ASC])
			->asArray()
			->all();

		return $this->render('CategoriesNavbar', [
			'lang' => $lang,
			'categories' => $categories
		]);
	}
}