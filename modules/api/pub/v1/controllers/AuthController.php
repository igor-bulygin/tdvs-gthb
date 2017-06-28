<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Login;
use app\models\Person;
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
			$person = Yii::$app->user->identity; /* @var Person $person */
			
			$message = sprintf("LOGIN: User %s logged succesfully via authcontroller", $person->getName());
			Yii::info($message, __METHOD__);
			
			Yii::$app->response->setStatusCode(200); // Created
			return [
				'access_token' => $person->getAccessToken(),
				'return_url' => Yii::$app->getUser()->getReturnUrl(),
			];

		} else {
			Yii::info('LOGIN: Invalid login', __METHOD__);

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
