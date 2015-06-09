<?php

namespace app\controllers;

use app\helpers\Utils;
use Yii;
use yii\web\Response;
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
	private $intern;

	public function __construct($id, $module, $config = [], $intern = false) {
		$this->intern = $intern;
		parent::__construct($id, $module, $config = []);
	}

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

	public function actionExample($filters = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, []);
				//TODO: If not admin, force some fields (enabled only, visible by public only, etc...)
			}

			$res = empty($filters) ? Tag::find() : Tag::find()->where($filters);

			if($this->intern === false) {
				$res = $res->asArray()->all();
			}
		} else if ($request->isPost) {
			$something = Utils::getJsonFromRequest("something");
			unset($something["_id"]);

			//TODO
		} else if ($request->isDelete) {
			$something = Utils::getJsonFromRequest("something");

			//TODO
		}

		return $res;
	}

	public function actionTags($filters = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {

			$filters = json_decode($filters, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ["short_id", "required", "type", "categories"]);

				//Force only enabled tags
				//$filters["enabled"] = true;
				//TODO: If not admin, force only enabled, etc...
			}

			$res = empty($filters) ? Tag::find() : Tag::find()->where($filters);

			if($this->intern === false) {
				$res = $res->asArray()->all();
			}
		} else if ($request->isPost) {
			$_tag = Utils::getJsonFromRequest("tag");
			unset($_tag["_id"]);

			if ($_tag["short_id"] === "new") {
				$_tag["short_id"] = (new Tag())->genValidID(5);
			}

			/* @var $tag \app\models\Tag */
			$tag = Tag::findOne(["short_id" => $_tag["short_id"]]);
			$tag->setAttributes($_tag, false);
			$tag->options = array_replace_recursive($tag->options, $_tag["options"]);
			$tag->save();

			$res = $tag;
		} else if ($request->isDelete) {
			$tag = Utils::getJsonFromRequest("tag");

			/* @var $tag \app\models\Tag */
			$tag = Tag::findOne(["short_id" => $tag["short_id"]]);
			$tag->delete();
		}

		return $res;
	}

	public function actionCategories($filters = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ["short_id"]);

				//TODO: If not admin, force only enabled, etc...
			}

			$res = empty($filters) ? Category::find() : Category::find()->where($filters);

			if($this->intern === false) {
				$res = $res->asArray()->all();
			}
		} else if ($request->isPost) {
			$node = Utils::getJsonFromRequest("category");
			unset($_node["_id"]);

			if ($node["short_id"] === "new") {
				$node["short_id"] = (new Category())->genValidID(5);
			}

			/* @var $category \app\models\Category */
			$category = Category::findOne(["short_id" => $node["short_id"]]);
			$category->setAttributes($node, false);
			$category->name = array_replace_recursive($category->name, $node["name"]);
			$category->save();

			$res = $category;
		} else if ($request->isDelete) {
			$node = Utils::getJsonFromRequest("category");

			/* @var $category \app\models\Category */
			$category = Category::findOne(["short_id" => $node["short_id"]]);
			$category->delete();
		}

		return $res;
	}
}
