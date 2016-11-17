<?php

namespace app\components;

use app\models\Category;
use app\models\Login;
use Yii;
use yii\base\Widget;
use app\helpers\Utils;

class PublicHeader2 extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		$model = new Login();
		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			//
		}

		return $this->render('PublicHeader2', [
			'categories' => Category::getHeaderCategories(),
			'login_model' => $model
		]);
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views', 'PublicHeader2');
	}
}
