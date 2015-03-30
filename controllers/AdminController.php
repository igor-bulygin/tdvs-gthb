<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\ViewContextInterface;

class AdminController extends Controller
{
	public $defaultAction = "index";

	public function init()
	{
		$dd = \Yii::$app->devicedetect;
		if ($dd->isMobile() && !$dd->isTablet()) {
			$this->layout = "/mobile/admin";
		} else if($dd->isTablet()) {
			$this->layout = "/tablet/admin";
		} else {
			$this->layout = "/desktop/admin";
		}
	}

	public function actionIndex()
	{
		return $this->render("index", [
			'dd' => \Yii::$app->devicedetect
		]);
	}
}
