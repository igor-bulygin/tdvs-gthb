<?php

namespace app\controllers;

use Yii;
use app\helpers\CController;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\base\ViewContextInterface;

class PublicController extends CController
{
	public $defaultAction = "index";

	public function actionIndex() {
		return $this->render("index");
	}

	public function actionCategory($category_id, $slug) {
		var_dump("adalshdajsdh");
		die();
	}

	public function actionProduct($category_id, $product_id, $slug) {
		echo Url::to(["/public/product", "category_id" => 12456, "product_id" => 987654, "slug" => "asfo-asdg-asd-gasd-gasdgsdgasdg-asdgasdg"]);
		echo "<br />";
		echo Url::to(["/public/category", "category_id" => 12456, "slug" => "asfo-asdg-asd-gasd-gasdgsdgasdg-asdgasdg"]);
		echo "<br />";
		echo "$category_id | $product_id | $slug";
		die();
	}
}
