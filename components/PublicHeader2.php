<?php

namespace app\components;

use app\helpers\Utils;
use app\models\Category;
use app\models\Login;
use Yii;
use yii\base\Widget;

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
			'login_model' => $model,
			'q' => Yii::$app->request->get('q'),
		]);
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views', 'PublicHeader2');
	}
}
