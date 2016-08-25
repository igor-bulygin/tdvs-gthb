<?php

namespace app\components;

use app\models\Category;
use Yii;
use yii\base\Widget;
use app\helpers\Utils;

class PublicHeader2 extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('PublicHeader2', [
			'categories' => Category::getHeaderCategories(),
		]);
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views', 'PublicHeader2');
	}
}
