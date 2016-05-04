<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\web\Response;
use app\helpers\CController;
use yii\filters\ContentNegotiator;

class GlobalController extends CController {

	public function behaviors() {
		return [
			'format' => [
				'class' => ContentNegotiator::className(),
				'formats' => [
					'application/json' => Response::FORMAT_JSON
				]
			]
		];
	}

	public function actionForgotPassword () {
		echo "Oops...";
		return;
	}

	public function actionLogout () {
		Yii::$app->user->logout();
		return $this->goBack();
	}

	public function actionSetFlash($key, $message) {
		Yii::$app->session->setFlash($key, $message);
		return true;
	}

	public function actionGetFlashes() {
		return Json::encode(Yii::$app->session->getAllFlashes());
	}

}
