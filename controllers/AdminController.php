<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\ViewContextInterface;

use yii\helpers\CController;

class AdminController extends CController
{
	public $defaultAction = "index";

	public function actionIndex()
	{
		return $this->render("index");
	}

	public function actionCategories()
	{
		return $this->render("index");
	}
}
