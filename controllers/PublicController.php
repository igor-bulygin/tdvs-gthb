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
use app\helpers\ModelUtils;
use app\helpers\CController;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\base\ViewContextInterface;
use app\models\Faq;

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
				$work['img'] = ModelUtils::getProductMainPhoto($work);
				$work["slug"] = @Utils::l($work["slug"]) ?: " ";
			}

			$deviser['works'] = $works;
			$deviser['name'] = ModelUtils::getDeviserFullName($deviser);
			$deviser['category'] = ModelUtils::getDeviserCategoriesNames($deviser)[0];
			$deviser['img'] = ModelUtils::getDeviserAvatar($deviser);
		}

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

			if (ModelUtils::getCategory($category['short_id']) !== null) {
				$match = [
					'categories' => [
						'$in' => array_map(function ($category) {
							return $category['short_id'];
						}, ModelUtils::getSubCategories($category['short_id']) )
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
				$product['category'] = ModelUtils::getProductCategoriesNames($product)[0];
				$product['img'] = ModelUtils::getProductMainPhoto($product);
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

		$tmp = Yii::$app->mongodb->getCollection('product')
			->aggregate(
				[
					'$project' => [
						"short_id" => 1, "slug" => 1, "categories" => 1, "name" => 1, "media" => 1
					]
				],
				[
					'$match' => [
						'categories' => [
							'$in' => array_map(function ($category) {
								return $category['short_id'];
							}, ModelUtils::getSubCategories($category_id) )
						]
					]
				]
			);

		foreach ($tmp as $key => &$product) {
			$product["name"] = @Utils::l($product["name"]) ?: " ";
			$product["slug"] = @Utils::l($product["slug"]) ?: " ";
			$product['category'] = ModelUtils::getProductCategoriesNames($product)[0];
			$product['img'] = ModelUtils::getProductMainPhoto($product);
		}

		$products = new ArrayDataProvider([
			'allModels' => $tmp,
			'pagination' => [
				'pagesize' => 40,
			],
		]);

		return $this->render("category", [
			'products' => $products
		]);
	}

	public function actionProduct($category_id, $product_id, $slug) {

		$product = Product::find()
			->where([
				"short_id" => $product_id
			])
			->asArray()
			->one();

		$product['name'] = Utils::l($product['name']);
		$product['description'] = Utils::l($product['description']);
		$product['category'] = ModelUtils::getProductCategoriesNames($product)[0];

		$tmp = Yii::$app->mongodb->getCollection('product')
			->aggregate(
				[
					'$project' => [
						"short_id" => 1, "slug" => 1, "categories" => 1, "name" => 1, "media" => 1
					]
				],
				[
					'$match' => [
						'deviser_id' => $product['deviser_id']
					]
				],
				[
					'$sample' => [
						'size' => 400
					]
				]
			);
		$tmp = Product::find()->where(['deviser_id' => $product['deviser_id']])->asArray()->all();

		Utils::l_collection($tmp, "name");
		Utils::l_collection($tmp, "slug");

		foreach ($tmp as $key => &$_product) {
			$_product['category'] = ModelUtils::getProductCategoriesNames($_product)[0];
			$_product['img'] = ModelUtils::getProductMainPhoto($_product);
		}

		$other_works = new ArrayDataProvider([
			'allModels' => $tmp,
			'pagination' => [
				'pagesize' => 40,
			],
		]);

		//404 if $product == null

		//$this->view->params["product_path"] = $product['categories'];

		$deviser = Person::find()
			->where([
				"short_id" => $product['deviser_id'],
				"type" => Person::DEVISER
			])
			->asArray()
			->one();

		$deviser['img'] = ModelUtils::getDeviserAvatar($deviser);
		$deviser['fullname'] = ModelUtils::getDeviserFullName($deviser);

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
			'other_works' => $other_works,
			'deviser' => $deviser,
			'tags' => $tags,
			'category_id' => $category_id,
			'product_id' => $product_id,
			'slug' => $slug
		]);
	}

	public function actionDeviser($deviser_id, $slug) {
		$deviser = Person::find()
			->where([
				"short_id" => $deviser_id,
				"type" => Person::DEVISER
			])
			->asArray()
			->one();

		$deviser['name'] = ModelUtils::getDeviserFullName($deviser);
		$deviser['category'] = ModelUtils::getDeviserCategoriesNames($deviser)[0];
		$deviser['img'] = ModelUtils::getDeviserAvatar($deviser);
		$deviser['img_header'] = ModelUtils::getDeviserHeader($deviser);

		$tmp = Product::find()->select(["_id" => 0])->where(["deviser_id" => $deviser_id])->asArray()->all();

		Utils::l_collection($tmp, "name");
		Utils::l_collection($tmp, "slug");

		foreach ($tmp as $key => &$_product) {
			$_product['category'] = ModelUtils::getProductCategoriesNames($_product)[0];
			$_product['img'] = ModelUtils::getProductMainPhoto($_product);
		}

		$works = new ArrayDataProvider([
			'allModels' => $tmp,
			'pagination' => [
				'pagesize' => 40,
			],
		]);

		return $this->render("deviser", [
			'deviser' => $deviser,
			'works' => $works
		]);
	}

	public function actionCart(){
		//Manage ajax query an return feedback
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			$res = array(
				'body'    => date('Y-m-d H:i:s'),
				'success' => true,
			);

			return $res;
		}

		//Show cart view
		return $this->render("cart", [
			'test' => 'this is a test text'
		]);
	}

	public function actionFaq(){
		$lang = Yii::$app->language;

		$answersAndQuestions = Faq::find()
			->where([
				"lang" => $lang
			])
			->asArray()->all();


		return $this->render("faq", [
			'test' => 'this is a test text for faq',
			'answersAndQuestions' => $answersAndQuestions
		]);
	}

}
