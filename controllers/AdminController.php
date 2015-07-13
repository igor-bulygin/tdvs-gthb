<?php

namespace app\controllers;

use Yii;
use app\models\Lang;
use yii\helpers\Json;
use app\helpers\Utils;
use app\models\Country;
use app\models\SizeChart;
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

	public function actionDevisers($filters = null) {
		$filters = urldecode($filters) ?: null;

		$devisers = new ActiveDataProvider([
			'query' => $this->api->actionDevisers($filters),
			'pagination' => [
				'pageSize' => 15,
			],
		]);

		$countries = $this->api->actionCountries()->asArray()->all();
		$countries_lookup = [];
		foreach($countries as $country) {
			$countries_lookup[$country["country_code"]] = Utils::getValue($country["country_name"], Yii::$app->language, array_keys(Lang::EN_US)[0]);
		}

		$data = [
			'devisers' => $devisers,
			"countries" => $countries,
			"countries_lookup" => $countries_lookup
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("devisers", $data) : $this->render("devisers", $data);
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

	public function actionSizeCharts($filters = null) {
		$filters = urldecode($filters) ?: null;
		$filters === null ? [] : Json::decode($filters);
		$filters["type"] = SizeChart::TODEVISE;

		$sizecharts = new ActiveDataProvider([
			'query' => $this->api->actionSizeCharts(Json::encode($filters)),
			'pagination' => [
				'pageSize' => 15,
			],
		]);

		$data = [
			'categories' => $this->api->actionCategories()->asArray()->all(),
			'sizecharts' => $sizecharts,
			'sizechart_template' => $this->api->actionSizeCharts("", Json::encode(["_id" => 0, "short_id", "name"]))->asArray()->all()
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("size-charts", $data) : $this->render("size-charts", $data);
	}

	public function actionSizeChart($size_chart_id) {
		$countries = $this->api->actionCountries()->asArray()->all();
		$countries_lookup = [];
		$continents = [];

		foreach($countries as $country) {
			$countries_lookup[$country["country_code"]] = Utils::getValue($country["country_name"], Yii::$app->language, array_keys(Lang::EN_US)[0]);
		}

		foreach(Country::CONTINENTS as $code => $continent) {
			$continents[] = [
				"country_code" => $code,
				"country_name" => [
					Yii::$app->language => Yii::t("app/admin", $continent)
				]
			];
			$countries_lookup[$code] = Yii::t("app/admin", $continent);
		}

		return $this->render("size-chart", [
			"sizechart" => $this->api->actionSizeCharts(Json::encode(["short_id" => $size_chart_id]))->asArray()->one(),
			"categories" => $this->api->actionCategories()->asArray()->all(),
			"countries" => [
				[
					"country_name" => [
						Yii::$app->language => Yii::t("app/admin", "Continents")
					],
					"sub" => $continents
				],
				[
					"country_name" => [
						Yii::$app->language => Yii::t("app/admin", "Countries")
					],
					"sub" => $countries
				]
			],
			"countries_lookup" => $countries_lookup
		]);
	}

	public function actionCategories() {
		return $this->render("categories", []);
	}
}
