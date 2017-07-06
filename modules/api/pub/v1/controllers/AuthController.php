<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Login;
use app\models\Person;
use Yii;
use yii\helpers\Url;

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

			$returnUrl = Yii::$app->getUser()->getReturnUrl();

			// If return url is "home", we redirect the user to the main link (profile)
			if ($returnUrl == Yii::$app->getHomeUrl()) {
				$returnUrl = $person->getMainLink();
			}
			if (Url::isRelative($returnUrl)) {
				$returnUrl = Url::to($returnUrl, true);
			}
			
			Yii::$app->response->setStatusCode(200); // Created
			return [
				'access_token' => $person->getAccessToken(),
				'return_url' => $returnUrl,
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
