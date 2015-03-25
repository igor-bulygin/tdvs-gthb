<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\ViewContextInterface;

class DeviserController extends Controller
{
	public $defaultAction = "index";

	public function actionIndex()
	{
		return $this->render("index");
	}
}
