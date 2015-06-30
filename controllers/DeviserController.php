<?php

namespace app\controllers;

use Yii;
use app\helpers\CController;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\ViewContextInterface;

class DeviserController extends CController {
	public $defaultAction = "index";

	public function actionIndex() {
		return $this->render("index");
	}

	public function actionEditInfo($slug) {
		$deviser = $this->api->actionDevisers(Json::encode(["slug" => $slug]))->asArray()->all();
		return $this->render("edit-info", [
			"deviser" => $deviser[0],
			"slug" => $slug
		]);
	}
}
