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
			->where(["path" => "/"])
			->orderBy(['name.' . $lang => SORT_ASC])
			->asArray()
			->all();

		return $this->render('LeftMenu', [
			'lang' => $lang,
			'categories' => $categories
		]);
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views', 'LeftMenu', Yii::getAlias('@device'));
	}
}
