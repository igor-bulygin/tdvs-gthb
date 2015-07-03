<?php

namespace app\controllers;

use Yii;
use yii\web\Response;
use app\helpers\Utils;
use app\models\Person;
use app\models\Country;
use app\models\SizeChart;
use yii\base\ActionFilter;
use yii\filters\VerbFilter;
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

	/*
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
			$something = $this->getJsonFromRequest("something");
			unset($something["_id"]);

			//TODO
		} else if ($request->isDelete) {
			$something = $this->getJsonFromRequest("something");

			//TODO
		}

		return $res;
	}
	*/

	public function actionCountries($filters = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, []);
				//TODO: If not admin, force some fields (enabled only, visible by public only, etc...)
			}

			$res = empty($filters) ? Country::find() : Country::find()->where($filters);

			if($this->intern === false) {
				$res = $res->asArray()->all();
			}
		} else if ($request->isPost) {
			$_country = $this->getJsonFromRequest("country");
			unset($_country["_id"]);

			if ($_country["short_id"] === "new") {
				$_country["short_id"] = (new Country())->genValidID(5);
			}

			/* @var $country \app\models\Country */
			$country = Country::findOne(["short_id" => $_country["short_id"]]);
			$country->setAttributes($_country, false);
			$country->save();

			$res = $country;
		} else if ($request->isDelete) {
			$country = $this->getJsonFromRequest("country");

			/* @var $country \app\models\Country */
			$country = Country::findOne(["short_id" => $country["short_id"]]);
			$country->delete();
		}

		return $res;
	}

	public function actionDevisers($filters = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, []);
				//TODO: If not admin, force some fields (enabled only, visible by public only, etc...)
			}
			$filters["type"]['$in'] = [Person::DEVISER];

			$res = empty($filters) ? Person::find() : Person::find()->where($filters);

			if($this->intern === false) {
				$res = $res->asArray()->all();
			}
		} else if ($request->isPost) {
			$_deviser = $this->getJsonFromRequest("person");
			unset($_deviser["_id"]);

			if ($_deviser["short_id"] === "new") {
				$_deviser["short_id"] = (new Person())->genValidID(7);
			}

			/* @var $deviser \app\models\Person */
			$deviser = Person::findOne(["short_id" => $_deviser["short_id"]]);
			$deviser->setAttributes($_deviser, false);
			$deviser->save();

			$res = $deviser;
		} else if ($request->isDelete) {
			$deviser = $this->getJsonFromRequest("person");

			/* @var $deviser \app\models\Person */
			$deviser = Person::findOne(["short_id" => $deviser["short_id"]]);
			$deviser->delete();
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
			$_tag = $this->getJsonFromRequest("tag");
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
			$tag = $this->getJsonFromRequest("tag");

			/* @var $tag \app\models\Tag */
			$tag = Tag::findOne(["short_id" => $tag["short_id"]]);
			$tag->delete();
		}

		return $res;
	}

	public function actionSizeCharts($filters = null, $fields = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$fields = json_decode($fields, true) ?: [];
			$filters = json_decode($filters, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ["short_id"]);
				//TODO: If not admin, force some fields (enabled only, visible by public only, etc...)
			}

			$res = empty($filters) ? SizeChart::find()->select($fields) : SizeChart::find()->select($fields)->where($filters);

			if($this->intern === false) {
				$res = $res->asArray()->all();
			}
		} else if ($request->isPost) {
			$_sizechart = $this->getJsonFromRequest("sizechart");
			unset($_sizechart["_id"]);

			if ($_sizechart["short_id"] === "new") {
				$_sizechart["short_id"] = (new SizeChart())->genValidID(5);
			}

			/* @var $sizechart \app\models\SizeChart */
			$sizechart = SizeChart::findOne(["short_id" => $_sizechart["short_id"]]);
			$sizechart->setAttributes($_sizechart, false);
			//$tag->options = array_replace_recursive($tag->options, $_tag["options"]);
			$sizechart->save();

			$res = $sizechart;
		} else if ($request->isDelete) {
			$sizechart = $this->getJsonFromRequest("sizechart");

			/* @var $sizechart \app\models\SizeChart */
			$sizechart = SizeChart::findOne(["short_id" => $sizechart["short_id"]]);
			$sizechart->delete();
		}

		return $res;
	}

	public function actionCategories($filters = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ["short_id", "path"]);

				//TODO: If not admin, force only enabled, etc...
			}

			$res = empty($filters) ? Category::find() : Category::find()->where($filters);

			if($this->intern === false) {
				$res = $res->asArray()->all();
			}
		} else if ($request->isPost) {
			$node = $this->getJsonFromRequest("category");
			unset($node["_id"]);

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
			$node = $this->getJsonFromRequest("category");

			/* @var $category \app\models\Category */
			$category = Category::findOne(["short_id" => $node["short_id"]]);
			$category->delete();
		}

		return $res;
	}
}
