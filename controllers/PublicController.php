<?php

namespace app\controllers;

use Yii;
use app\helpers\CController;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\ViewContextInterface;

class PublicController extends CController
{
	public $defaultAction = "index";

	public function actionIndex()
	{
		return $this->render("index");
	}
}
