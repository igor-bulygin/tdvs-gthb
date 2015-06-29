<?php

namespace app\components;

use Yii;
use yii\base\Widget;
use app\models\Category;

class CategoriesList extends Widget {
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

		return $this->render('CategoriesList', [
			'lang' => $lang,
			'categories' => $categories
		]);
	}
}