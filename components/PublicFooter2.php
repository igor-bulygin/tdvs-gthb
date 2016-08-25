<?php

namespace app\components;

use app\models\Category;
use Yii;
use yii\base\Widget;
use app\helpers\Utils;

class PublicFooter2 extends Widget {

	public function run() {
		return $this->render('PublicFooter2', [
			'categories' => Category::getFooterCategories(),
		]);
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views', 'PublicFooter2');
	}
}
