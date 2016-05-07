<?php

namespace app\components;

use Yii;
use yii\base\Widget;
use app\models\Login;
use app\helpers\Utils;

class PublicMyAccount extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		$model = new Login();

		if ($model->load(Yii::$app->request->post()) && $model->login()) {
			//
		}

		return $this->render('PublicMyAccount', [
			'login_model' => $model
		]);
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views', 'PublicMyAccount', Yii::getAlias('@device'));
	}
}
