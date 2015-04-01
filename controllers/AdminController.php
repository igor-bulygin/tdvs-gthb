<?php

namespace app\controllers;

use Yii;
use yii\filters\AccessControl;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\ViewContextInterface;

class AdminController extends Controller implements ViewContextInterface
{
	public $defaultAction = "index";
	public $viewPath = "";

	public function init()
	{
		if (\Yii::$app->params['devicedetect']['isMobile']) {
			$this->layout = '/mobile/admin';
			$this->viewPath = '@app/views/admin/mobile';
		} else if(\Yii::$app->params['devicedetect']['isTablet']) {
			$this->layout = '/tablet/admin';
			$this->viewPath = '@app/views/admin/tablet';
		} else {
			$this->layout = '/desktop/admin';
			$this->viewPath = '@app/views/admin/desktop';
		}
	}

	public function getViewPath()
	{
		return Yii::getAlias($this->viewPath);
	}

	public function actionIndex()
	{
		return $this->render("index");
	}

	public function actionCategories()
	{
		return $this->render("index");
	}
}
