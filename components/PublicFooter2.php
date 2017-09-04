<?php

namespace app\components;

use app\helpers\Utils;
use app\models\Category;
use Yii;
use yii\base\Widget;

class PublicFooter2 extends Widget {

	public function run() {
		return $this->render('PublicFooter2', [
			'categories' => Category::getFooterCategories(),
		]);
	}

	public function getViewPath() {
		if (Yii::getAlias('@device') != 'desktop') {
			return Utils::join_paths('@app', 'components', 'views', 'PublicFooter2', Yii::getAlias('@device'));
		}
		return Utils::join_paths('@app', 'components', 'views', 'PublicFooter2');
	}
}
