<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Person;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class UserController extends AppPrivateController
{

	public function actionView()
	{
		/** @var Person $user */
		$user = $this->getPerson();

		return $user;
	}

	public function actionUpdate()
	{
		return ["action" => "update (user)"];
	}

}

