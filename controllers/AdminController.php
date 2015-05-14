<?php

namespace app\controllers;

use Yii;
use yii\helpers\Json;
use yii\mongodb\Query;
use app\models\Category;
use yii\filters\VerbFilter;
use app\helpers\CController;


class AdminController extends CController {
	public $defaultAction = "index";

	public function actionIndex()
	{
		return $this->render("index");
	}

	public function actionCategories() {
		$request = Yii::$app->getRequest();

		if ($request->isAjax && $request->isGet) {
			$categories = Category::find()->asArray()->all();
			usort($categories, function($a, $b) {
				return mb_strlen($a["path"]) > mb_strlen($b["path"]);
			});

			return $categories;
		} else if ($request->isAjax && $request->isPost) {
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

			return $category;
		} else if ($request->isAjax && $request->isDelete) {
			$node = Json::decode($request->getRawBody());
			$node = $node["category"];

			/* @var $category \app\models\Category */
			$category = Category::findOne(["short_id" => $node["short_id"]]);
			$category->delete();

			return null;
		}

		return $this->render("categories", []);
	}
}
