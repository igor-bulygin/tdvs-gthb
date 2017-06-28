<?php

namespace app\modules\api\pub\v1\controllers;

use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\rest\Controller;

class AppPublicController extends Controller
{
	public function init()
	{
		parent::init();

		\Yii::$app->user->enableSession = false;   // restfull must be stateless => no session
		\Yii::$app->user->loginUrl = null;         // force 403 response
	}

	public function behaviors()
	{
		$behaviors = parent::behaviors();

		$behaviors['authenticator'] = [
			'class' => HttpBearerAuth::className(),
			'optional' => ['*'], // in public api, BearerAuth is optional in all the actions
		];
		$behaviors['access'] = [
				'class' => AccessControl::className(),
				'rules' => [
						[
								'allow' => true,
								'roles' => ['?', '@'] //? guest, @ authenticated
						]
				],
		];

		return $behaviors;
	}

	public function beforeAction($action)
	{
		$message =
			"\nPUBLIC API ACTION".
			"\n - url => " . Url::current() .
			"\n - http_authorization => " . (isset($_SERVER["HTTP_AUTHORIZATION"]) ? $_SERVER["HTTP_AUTHORIZATION"] : "") .
			"\n - body_params => " . \Yii::$app->request->rawBody;
		\Yii::info($message, __METHOD__);

		return parent::beforeAction($action);
	}

}