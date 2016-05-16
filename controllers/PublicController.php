<?php

namespace app\controllers;

use Yii;
use app\models\Tag;
use yii\helpers\Url;
use app\models\Person;
use app\helpers\Utils;
use yii\web\Controller;
use app\models\Product;
use app\models\Category;
use yii\base\DynamicModel;
use yii\filters\VerbFilter;
use app\helpers\CController;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use yii\base\ViewContextInterface;

class PublicController extends CController {
	public $defaultAction = "index";

	public function actionError() {
		//error_log("Error public", 4);
		//die();
	}

	public function actionIndex() {
		$lang = Yii::$app->language;

		$banners = [];
		for ($i = 0; $i < 15; $i++) {
			$banners[] = [
				'img' => 'https://unsplash.it/1200/600/?random&t=' . $i,
				'caption' => [
					'name' => 'Foo bar ' . $i,
					'category' => 'Foo bar ' . $i
				]
			];
		}

		$categories_map = [];
		$categories_in_top_lvl = [];
		foreach (Category::find()->asArray()->all() as $key => $category) {
			$categories_map[$category['short_id']] = $category;
			$categories_map[$category['short_id']]['name'] = Utils::l($category['name']);

			if ($category['path'] == "/") {
				$categories_in_top_lvl[$category['short_id']] = [];
			}
			$path = explode("/", $category['path']);
			if (count($path) >= 3) {
				$categories_in_top_lvl[$path[1]][] = $category['short_id'];
			}
		}

		//TODO: Fix, this should be random
		$devisers = Yii::$app->mongodb->getCollection('person')
			->aggregate(
				[
					'$match' => [
						"type" => [
							'$in' => [
								Person::DEVISER
							]
						]
					]
				],
				[
					'$sample' => [
						'size' => 20
					]
				]
			);

		foreach ($devisers as $key => &$deviser) {
			$works = Yii::$app->mongodb->getCollection('product')
				->aggregate(
					[
						'$project' => [
							"short_id" => 1, "media" => 1, "slug" => 1, "deviser_id" => 1, "categories" => 1
						]
					],
					[
						'$match' => [
							"deviser_id" => $deviser["short_id"]
						]
					],
					[
						'$sample' => [
							'size' => 4
						]
					]
				);

			foreach ($works as $key => &$work) {
				if (isset($work["media"]) && isset($work["media"]["photos"])) {
					foreach ($work["media"]["photos"] as $key => $photo) {
						if (isset($photo["main_product_photo"]) && $photo["main_product_photo"]) {
							$work["img"] = Yii::getAlias("@product_url") . "/" . $work["short_id"] . "/" . $photo["name"];
						}
					}
				}
				if (!isset($work["img"])) {
					if (count($work["media"]["photos"]) == 0) {
						$work["img"] = 'https://unsplash.it/200/200/?random&t=' . $work['short_id'];
					} else {
						$work["img"] = Yii::getAlias("@product_url") . "/" . $work["short_id"] . "/" . $work["media"]["photos"][0]["name"];
					}
				}

				$work["slug"] = @Utils::l($work["slug"]) ?: " ";
			}

			$deviser['works'] = $works;
			$deviser['name'] = $deviser['personal_info']['name'] . ' ' . implode(" ", $deviser['personal_info']['surnames']);
			$deviser['category'] = @$categories_map[$deviser['categories'][0]]['name'];

			if (isset($deviser['media']) && isset($deviser['media']['profile'])) {
				$deviser['img'] = Yii::getAlias("@deviser_url") . "/" . $deviser["short_id"] . "/" . $deviser['media']['profile'];
			} else {
				$deviser['img'] = 'https://unsplash.it/200/200/?random&t=' . $deviser['short_id'];
			}
		}

		////////////////////

		$categories = Category::find()
			->where(["path" => "/"])
			->orderBy(['name.' . $lang => SORT_ASC])
			->asArray()
			->all();

		Utils::l_collection($categories, "name");

		array_unshift($categories, [
			'short_id' => 'all',
			'name' => Yii::t("app/public", 'All categories')
		]);

		$session = Yii::$app->session;
		foreach ($categories as &$category) {
			$model = new DynamicModel([
				'selected' => null
			]);
			$model->addRule('selected', 'string');

			$req = Yii::$app->request;
			if ($req->isPjax && substr($req->headers->get('X-PJAX-Container', ''), 1) === $category['short_id']) {
				if ($model->load($req->post())) {
					$session->set('index_cat_' . $category['short_id'], $model);
				}
			}

			if ($session->has('index_cat_' . $category['short_id'])) {
				$category['filter_model'] = $session->get('index_cat_' . $category['short_id']);
			} else {
				$category['filter_model'] = $model;
			}
		}

		foreach ($categories as $i => &$category) {
			//TODO: Add filter...
			/*
				$f = $category['filter_model']->selected;
				if($f === 'odd' && $j % 2 === 0) {
					continue;
				} else if ($f === 'even' && $j % 2 !== 0) {
					continue;
				}
			*/

			if (isset($categories_map[$category['short_id']])) {
				$match = [
					'categories' => [
						'$in' => $categories_in_top_lvl[$category['short_id']]
					]
				];
			} else {
				$match = [
					'short_id' => ['$gt' => ''] // We get here only with the 'all' (special) category, that's why we use a dummy filter to match everything.
				];
			}
			$tmp = Yii::$app->mongodb->getCollection('product')
				->aggregate(
					[
						'$project' => [
							"short_id" => 1, "slug" => 1, "categories" => 1, "name" => 1, "media" => 1
						]
					],
					[
						'$match' => $match
					],
					[
						'$sample' => [
							'size' => 400
						]
					]
				);

			foreach ($tmp as $key => &$product) {
				$product["name"] = @Utils::l($product["name"]) ?: " ";
				$product["slug"] = @Utils::l($product["slug"]) ?: " ";
				$product['category'] = @$categories_map[$product['categories'][0]]['name'];

				if (isset($product["media"]) && isset($product["media"]["photos"])) {
					foreach ($product["media"]["photos"] as $key => $photo) {
						if (isset($photo["main_product_photo"]) && $photo["main_product_photo"]) {
							$product["img"] = Yii::getAlias("@product_url") . "/" . $product["short_id"] . "/" . $photo["name"];
						}
					}
				}
				if (!isset($product["img"])) {
					if (count($product["media"]["photos"]) == 0) {
						$product["img"] = 'https://unsplash.it/200/200/?random&t=' . $product['short_id'];
					} else {
						$product["img"] = Yii::getAlias("@product_url") . "/" . $product["short_id"] . "/" . $product["media"]["photos"][0]["name"];
					}
				}
			}

			$category['products'] = new ArrayDataProvider([
				'allModels' => $tmp,
				'pagination' => [
					'pagesize' => 40,
				],
			]);
		}

		return $this->render("index", [
			'banners' => $banners,
			'devisers' => $devisers,
			'categories' => $categories
		]);
	}

	public function actionCategory($category_id, $slug) {
		var_dump("public / category");
		die();
	}

	public function actionProduct($category_id, $product_id, $slug) {

		$product = Product::findOne([
			"short_id" => $product_id
		]);

		//404 if $product == null

		//$this->view->params["product_path"] = $product['categories'];

		$deviser = Person::find([
			"short_id" => $product->deviser_id
		])->asArray()->one();

		if (isset($deviser->media['profile'])) {
			$deviser['img'] = Yii::getAlias('@deviser_url') . "/" . $deviser['short_id'] . "/" . $deviser['media']['profile'];
		} else {
			$deviser['img'] = 'https://unsplash.it/300/200/?random&t=' . $deviser['short_id'];
		}

		$tags = Tag::find()
		->where([
			"short_id" => [
				'$in' => array_map(function ($v) {
					return '' . $v;
				}, array_keys($product['options']))
			]
		])
		->asArray()
		->all();

		return $this->render("product", [
			'product' => $product,
			'deviser' => $deviser,
			'tags' => $tags,
			'category_id' => $category_id,
			'product_id' => $product_id,
			'slug' => $slug
		]);
	}
}
