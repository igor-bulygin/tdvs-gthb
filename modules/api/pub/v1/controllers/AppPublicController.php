<?php

namespace app\modules\api\pub\v1\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\rest\Controller;

class AppPublicController extends Controller
{
	public function init()
	{
		parent::init();

		\Yii::$app->user->enableSession = false; // restfull must be stateless => no session
		\Yii::$app->user->loginUrl = null;         // force 403 response
	}

	public function behaviors()
	{
		$behaviors = parent::behaviors();
		$behaviors['access'] = [
				'class' => AccessControl::className(),
			//'only' => ['', ''],
				'rules' => [
						[
								'allow' => true,
								'roles' => ['?', '@'] //? guest, @ authenticated
						]
				]
		];

		return $behaviors;
	}

}