<?php

namespace app\controllers;

use Yii;
use app\helpers\CController;
use yii\data\ActiveDataProvider;
use yii\helpers\Json;


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

		$data = [
			'categories' => $this->api->actionCategories()->asArray()->all(),
			'tags' => $tags
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("tags", $data) : $this->render("tags", $data);
	}

	public function actionTag($tag_id) {
		return $this->render("tag", [
			"tag" => $this->api->actionTags(Json::encode(["short_id" => $tag_id]))->asArray()->one(),
			"categories" => $this->api->actionCategories()->asArray()->all()
		]);
	}

	public function actionCategories() {
		return $this->render("categories", []);
	}
}
