<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\ViewContextInterface;

class PublicController extends Controller implements ViewContextInterface
{
	public $defaultAction = "index";

	public function getViewPath()
	{
		return "@app/views/site";
	}

	public function actionIndex()
	{
		return $this->render("index");
	}
}
