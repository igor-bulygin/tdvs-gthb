<?php

namespace app\controllers;

use Yii;
use app\models\Tag;
use yii\helpers\Url;
use app\models\Person;
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

		$devisers = [];
		for ($i = 0; $i < 20; $i++) {
			$works = [];

			for ($j = 0; $j < 4; $j++) {
				$works[] = [
					'short_id' => $j,
					'img' => 'https://unsplash.it/100/100/?random&t=' . $j,
					'slug' => 'foo-bar'
				];
			}

			$devisers[] = [
				'name' => 'Foo bar ' . $i,
				'category' => 'Foo bar',
				'slug' => 'foo-bar',
				'img' => 'https://unsplash.it/200/200/?random&t=' . $i,
				'works' => $works
			];
		}

		////////////////////

		$categories = Category::find()
			->where(["path" => "/"])
			->orderBy(['name.' . $lang => SORT_ASC])
			->asArray()
			->all();

		array_unshift($categories, [
			'short_id' => '123456',
			'name' => [
				'en-US' => 'All categories'
			],
			'products' => []
		]);

		foreach ($categories as &$category) {
			$model = new DynamicModel([
				'selected' => null
			]);
			$model->addRule('selected', 'string');

			$req = Yii::$app->request;
			if ($req->isPjax && substr($req->headers->get('X-PJAX-Container', ''), 1) === $category['short_id']) {
				$model->load($req->post());
			}
			$category['filter_model'] = $model;
			error_log($category['filter_model']->selected, 4);
		}

		foreach ($categories as $i => &$category) {
			$tmp = [];
			for ($j = 0; $j < 300; $j++) {

				$f = $category['filter_model']->selected;
				if($f === 'odd' && $j % 2 === 0) {
					continue;
				} else if ($f === 'even' && $j % 2 !== 0) {
					continue;
				}

				$tmp[] = [
					'product_id' => '1234567',
					'img' => 'https://unsplash.it/300/200/?random&t=' . $i . $j,
					'width' => 600,
					'height' => 400,
					'name' => 'Foo bar ' . $j,
					'price' => $j,
					'category_id' => $category['short_id'],
					'slug' => 'foo-bar'
				];
			}

			$category['products'] = new ArrayDataProvider([
				'allModels' => $tmp
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

		$deviser = Person::findOne([
			"short_id" => $product->deviser_id
		]);

		$tags = Tag::find()
		->where([
			"short_id" => [
				'$in' => array_map(function ($v) { return '' . $v; }, array_keys($product['options']))
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
