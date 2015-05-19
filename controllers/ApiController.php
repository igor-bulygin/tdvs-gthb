<?php

namespace app\controllers;

use app\helpers\Utils;
use Yii;
use yii\web\Response;
use yii\helpers\Json;
use yii\base\ActionFilter;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\filters\ContentNegotiator;

use app\models\Tag;
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

	public function actionExample() {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {

		} else if ($request->isPost) {

		} else if ($request->isDelete) {

		}

		return $res;
	}

	public function actionTags($filters = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		//error_log(print_r($filters, true), 4);

		if ($request->isGet) {
			if (!is_array($filters)) {
				//JSON value from GET or empty array
				$filters = json_decode($filters, true) ?: array();

				//Filter only allowed keys
				$allowed_filters = array("short_id", "required", "type", "categories");
				$filters = array_intersect_key($filters, array_flip($allowed_filters));

				//Force only enabled tags
				$filters["enabled"] = true;
			}

			$res = Tag::findAll($filters);
		} else if ($request->isPost) {

		} else if ($request->isDelete) {

		}

		return $res;
	}

	public function actionCategories() {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$res = Category::find()->asArray()->all();
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
