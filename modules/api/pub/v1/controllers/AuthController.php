<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Login;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;

class AuthController extends AppPublicController
{
	public function init()
	{
		parent::init();

		\Yii::$app->user->enableSession = true;
	}

	public function actionLogin()
	{
		try {
			$model = new Login();
			if ($model->load(Yii::$app->getRequest()->getBodyParams(), '') && $model->login()) {

				Yii::$app->response->setStatusCode(200); // Created
				return ['access_token' => Yii::$app->user->identity->getAccessToken()];

			} else {
				Yii::$app->response->setStatusCode(400); // Bad Request
				return ["errors" => $model->errors];
			}
		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}

	public function actionLogout()
	{
		try {
			Yii::$app->user->logout();
			Yii::$app->response->setStatusCode(204); // No content

			return null;

		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}
}
