<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use yii\helpers\Json;
use yii\base\ActionFilter;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;

use app\models\Category;
use app\helpers\CController;

class IsAjaxFilter extends ActionFilter {
	public function beforeAction($action) {
		return Yii::$app->getRequest()->isAjax;
	}
}

class ApiController extends CController {

	public function behaviors() {
		return [
			'format' => [
				'class' => ContentNegotiator::className(),
				'formats' => [
					'application/json' => Response::FORMAT_JSON
				]
			],

			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'categories' => ['get', 'post', 'delete']
				]
			],

			'ajax' => [
				'class' => IsAjaxFilter::className()
			],

			/*
			'access' => [
				'class' => AccessControl::className(),
				//'only' => ['', ''],
				'rules' => [
					[
						'allow' => true,
						'roles' => ['?', '@'] //? guest, @ authenticated
					]
				]
			]
			*/
		];
	}

	//$subcategories = Category::findAll(["path" => "/^$current_path/"]);
	//$query = new Query;
	//$query->select([])->from('category')->where(["path" => ['$in' => [new \MongoRegex("/^/10000/"), new \MongoRegex("/^/70000/")] ]]);
	//$subcategories = $query->all();
	//var_dump($subcategories);

	public function actionTags() {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {

		}
	}

	public function actionCategories() {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$categories = Category::find()->asArray()->all();
			usort($categories, function($a, $b) {
				return mb_strlen($a["path"]) > mb_strlen($b["path"]);
			});

			$res = $categories;
		} else if ($request->isPost) {
			$node = Json::decode($request->getRawBody());
			$node = $node["category"];

			if ($node["short_id"] === "new") {
				$node["short_id"] = (new Category())->genValidID(5);
			}

			/* @var $category \app\models\Category */
			$category = Category::findOne(["short_id" => $node["short_id"]]);
			$category->setAttributes($node, false);
			$category->name = array_merge($category->name, $node["name"]);
			$category->save();

			$res = $category;
		} else if ($request->isDelete) {
			$node = Json::decode($request->getRawBody());
			$node = $node["category"];

			/* @var $category \app\models\Category */
			$category = Category::findOne(["short_id" => $node["short_id"]]);
			$category->delete();

			$res = null;
		}

		return $res;
	}
}
