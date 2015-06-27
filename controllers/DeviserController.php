<?php

namespace app\controllers;

use Yii;
use app\helpers\CController;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\ViewContextInterface;

class DeviserController extends CController {
	public $defaultAction = "index";

	public function actionIndex() {
		return $this->render("index");
	}

	public function actionEditInfo($slug) {
		return $slug;
	}
}
