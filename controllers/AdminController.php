<?php

namespace app\controllers;

use Yii;
use app\models\Tag;
use yii\helpers\Json;
use app\helpers\Utils;
use app\models\Person;
use app\models\Country;
use app\models\Product;
use app\models\Category;
use app\models\SizeChart;
use app\models\MetricType;
use yii\filters\VerbFilter;
use app\helpers\CAccessRule;
use app\helpers\CController;
use yii\filters\AccessControl;
use yii\data\ActiveDataProvider;

class AdminController extends CController {
	public $defaultAction = "index";

	public function behaviors () {
		return [
			'access' => [
				'class' => AccessControl::className(),
				'ruleConfig' => [
					'class' => CAccessRule::className(),
				],
				'rules' => [
					[
						'allow' => true,
						'roles' => ['admin'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

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
		$filters = Utils::stringToFilter($filters);
		$filters["type"]['$in'] = [Person::DEVISER];

		$devisers = new ActiveDataProvider([
			'query' => Person::find()->where($filters),
			'pagination' => [
				'pageSize' => 15,
			],
		]);

		$countries = Country::find()->asArray()->all();
		Utils::l_collection($countries, "country_name");

		$countries_lookup = [];
		foreach($countries as $country) {
			$countries_lookup[$country["country_code"]] = $country["country_name"];
		}

		$data = [
			'devisers' => $devisers,
			"countries" => $countries,
			"countries_lookup" => $countries_lookup
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("devisers", $data) : $this->render("devisers", $data);
	}

	public function actionAdmins($filters = null) {
		$filters = Utils::stringToFilter($filters);
		$filters["type"]['$in'] = [Person::ADMIN];

		$admins = new ActiveDataProvider([
			'query' => Person::find()->where($filters),
			'pagination' => [
				'pageSize' => 15,
			],
		]);

		$data = [
			'admins' => $admins
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("admins", $data) : $this->render("admins", $data);
	}

	public function actionTags($filters = null) {
		$filters = Utils::stringToFilter($filters);

		$tags = new ActiveDataProvider([
			'query' => Tag::find()->where($filters),
			'pagination' => [
				'pageSize' => 15,
			],
		]);

		$data = [
			'categories' => Category::find()->asArray()->all(),
			'tags' => $tags
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("tags", $data) : $this->render("tags", $data);
	}

	public function actionTag($tag_id) {
		return $this->render("tag", [
			"tag" => Tag::find()->where(["short_id" => $tag_id])->asArray()->one(),
			"categories" => Category::find()->asArray()->all(),
			"mus" => [
				["value" => MetricType::NONE, "text" => Yii::t("app/admin", MetricType::TXT[MetricType::NONE]), "checked" => true],
				["value" => MetricType::SIZE, "text" => Yii::t("app/admin", MetricType::TXT[MetricType::SIZE])],
				["value" => MetricType::WEIGHT, "text" => Yii::t("app/admin", MetricType::TXT[MetricType::WEIGHT])]
			]
		]);
	}

	public function actionSizeCharts($filters = null) {
		$filters = Utils::stringToFilter($filters);
		$filters["type"] = SizeChart::TODEVISE;

		$sizecharts = new ActiveDataProvider([
			'query' => SizeChart::find()->where($filters),
			'pagination' => [
				'pageSize' => 15,
			],
		]);

		$data = [
			'categories' => Category::find()->asArray()->all(),
			'sizecharts' => $sizecharts,
			'sizechart_template' => SizeChart::find()->select(["_id" => 0, "short_id", "name"])->asArray()->all()
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("size-charts", $data) : $this->render("size-charts", $data);
	}

	public function actionSizeChart($size_chart_id) {
		$countries = Country::find()->asArray()->all();
		$countries_lookup = [];
		$continents = [];

		foreach($countries as $country) {
			$countries_lookup[$country["country_code"]] = Utils::l($country["country_name"]);
		}

		foreach(Country::CONTINENTS as $code => $continent) {
			$continents[] = [
				"country_code" => $code,
				"country_name" => [
					$this->lang => Yii::t("app/admin", $continent)
				]
			];
			$countries_lookup[$code] = Yii::t("app/admin", $continent);
		}

		$sizechart = SizeChart::find()->select(["_id" => 0])->where(["short_id" => $size_chart_id])->asArray()->one();
		$categories = Category::find()->select(["_id" => 0])->asArray()->all();
		Utils::l_collection($categories, "name");

		$countries = [
			[
				"country_name" => [
					$this->lang => Yii::t("app/admin", "Continents")
				],
				"sub" => $continents
			],
			[
				"country_name" => [
					$this->lang => Yii::t("app/admin", "Countries")
				],
				"sub" => $countries
			]
		];

		$mus = [
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::SIZE]),
				"sub" => array_map(function($x) { return ["text" => $x, "value" => $x, "smallest" => MetricType::UNITS[MetricType::SIZE][0]]; }, MetricType::UNITS[MetricType::SIZE])
			],
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::WEIGHT]),
				"sub" => array_map(function($x) { return ["text" => $x, "value" => $x, "smallest" => MetricType::UNITS[MetricType::WEIGHT][0]]; }, MetricType::UNITS[MetricType::WEIGHT])
			]
		];

		return $this->render("size-chart", [
			"sizechart" => $sizechart,
			"categories" => $categories,
			"countries" => $countries,
			"countries_lookup" => $countries_lookup,
			"mus" => $mus
		]);
	}

	public function actionCategories() {
		return $this->render("categories", []);
	}

	public function actionProducts ($slug) {
		$deviser = Person::find()->where(["slug" => $slug, "type" => ['$in' => [Person::DEVISER]]])->asArray()->one();

		$products = new ActiveDataProvider([
			'query' => Product::find()->where(['deviser_id' => $deviser['short_id']]),
			'pagination' => [
				'pageSize' => 15,
			],
		]);

		$data = [
			'deviser' => $deviser,
			'products' => $products
		];

		return Yii::$app->request->isAjax ? $this->renderPartial("products", $data) : $this->render("products", $data);
	}
}
