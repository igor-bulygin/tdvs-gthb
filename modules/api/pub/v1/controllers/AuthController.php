<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Login;
use Yii;

class AuthController extends AppPublicController
{
	public function init()
	{
		parent::init();

		\Yii::$app->user->enableSession = true; // this controller must save authentication info on sesion
	}

	public function actionLogin()
	{
		$model = new Login();
		if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {

			Yii::$app->response->setStatusCode(200); // Created
			return [
				'access_token' => Yii::$app->user->identity->getAccessToken(),
				'return_url' => Yii::$app->getUser()->getReturnUrl(),
			];

		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $model->errors];
		}
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();
		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}
}
