<?php

namespace app\controllers;

use Yii;
use app\helpers\CController;


class AdminController extends CController {
	public $defaultAction = "index";

	public function actionIndex() {
		/*
		var_dump( (new Currency("EUR", 2))->convert("EUR") );
		var_dump( (new Currency("EUR", 10))->convert("USD") );
		var_dump( (new Currency("JPY", 130))->convert("EUR") );
		var_dump( (new Currency("JPY", 130))->convert("USD") );
		die();
		*/

		return $this->render("index");
	}

	public function actionTags() {
		return $this->render("tags", []);
	}

	public function actionCategories() {
		return $this->render("categories", []);
	}
}
