<?php

namespace app\controllers;

use Yii;
use app\helpers\CController;
use yii\data\ActiveDataProvider;


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

	public function actionTags($filters = null) {
		$filters = urldecode($filters) ?: null;

		$tags = new ActiveDataProvider([
			'query' => $this->api->actionTags($filters),
			'pagination' => [
				'pageSize' => 15,
			],
		]);

		return $this->render("tags", [
			'categories' => $this->api->actionCategories()->asArray()->all(),
			'tags' => $tags
		]);

	}

	public function actionTag($tag_id) {
		$model = null;
		return $this->render("tag", [
			"model" => $model
		]);
	}

	public function actionCategories() {
		return $this->render("categories", []);
	}
}
