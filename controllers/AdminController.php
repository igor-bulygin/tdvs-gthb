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
		if (\Yii::$app->params['devicedetect']['isMobile']) {
			$this->layout = "/mobile/admin";
		} else if(\Yii::$app->params['devicedetect']['isTablet']) {
			$this->layout = "/tablet/admin";
		} else {
			$this->layout = "/desktop/admin";
		}
	}

	public function actionIndex()
	{
		return $this->render("index");
	}
}
