<?php

namespace app\controllers;

use app\helpers\CController;
use app\helpers\Utils;
use app\models\Category;
use app\models\Country;
use app\models\Faq;
use app\models\OldProduct;
use app\models\Person;
use app\models\SizeChart;
use app\models\Tag;
use app\models\Term;
use app\models\PaymentErrors;
use Stripe\Stripe;
use Yii;
use yii\base\ActionFilter;
use yii\base\Exception;
use yii\filters\ContentNegotiator;
use yii\filters\VerbFilter;
use yii\web\Response;

class IsAjaxFilter extends ActionFilter {
	public function beforeAction($action) {
		return Yii::$app->getRequest()->isAjax;
	}
}

class ApiController extends CController {

	public function behaviors()
	{
		$behaviors = parent::behaviors();

		$behaviors['format'] = [
				'class' => ContentNegotiator::className(),
				'formats' => [
						'application/json' => Response::FORMAT_JSON
				]
		];

		$behaviors['verbs'] = [
				'class' => VerbFilter::className(),
				'actions' => [
						'categories' => ['get', 'post', 'delete']
				]
		];

		$behaviors['ajax'] = [
				'class' => IsAjaxFilter::className()
		];

//		$behaviors['access'] = [
//				'class' => AccessControl::className(),
//			//'only' => ['', ''],
//				'rules' => [
//						[
//								'allow' => true,
//								'roles' => ['?', '@'] //? guest, @ authenticated
//						]
//				]
//		];

		return $behaviors;
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
			$fields = array_merge(["_id" => 0], $fields);

			$res = empty($filters) ? Tag::find() : Tag::find()->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$something = $this->getJsonFromRequest("something");

			//TODO
		} else if ($request->isDelete) {
			$something = $this->getJsonFromRequest("something");

			//TODO
		}

		return $res;
	}
	*/

	public function actionCountries($filters = null, $fields = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];
			$fields = json_decode($fields, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, []);
				//TODO: If not admin, force some fields (enabled only, visible by public only, etc...)
			}
			$fields = array_merge(["_id" => 0], $fields);

			$res = empty($filters) ? Country::find()->select($fields) : Country::find()->select($fields)->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$_country = $this->getJsonFromRequest("country");

			if ($_country["short_id"] === "new") {
				$_country["short_id"] = (new Country())->genValidID(5);
			}

			/* @var $country \app\models\Country */
			$country = Country::findOne(["short_id" => $_country["short_id"]]);
			$country->setAttributes($_country, false);
			$country->save(false);

			$res = $country;
		} else if ($request->isDelete) {
			$country = $this->getJsonFromRequest("country");

			/* @var $country \app\models\Country */
			$country = Country::findOne(["short_id" => $country["short_id"]]);
			$country->delete();
		}

		return $res;
	}

	public function actionAdmins($filters = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ['short_id']);
				//TODO: If not admin, force some fields (enabled only, visible by public only, etc...)
			}
			$filters["type"]['$in'] = [Person::ADMIN];

			$res = empty($filters) ? Person::find() : Person::find()->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$_admin = $this->getJsonFromRequest("person");

			if ($_admin["short_id"] === "new") {
				$_admin["short_id"] = (new Person())->genValidID(7);
			}

			/* @var $admin \app\models\Person */
			$admin = Person::findOne(["short_id" => $_admin["short_id"]]);
			$password = $_admin["credentials"]["password"];
			unset($_admin["credentials"]["password"]);
			$admin->setAttributes($_admin, false);
			$admin->setPassword($password);
			$admin->save(false);

			$res = $admin;
		} else if ($request->isDelete) {
			$admin = $this->getJsonFromRequest("person");

			/* @var $admin \app\models\Person */
			$admin = Person::findOne(["short_id" => $admin["short_id"]]);
			$admin->delete();
		}

		return $res;
	}

	public function actionDevisers($filters = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ['short_id']);
				//TODO: If not admin, force some fields (enabled only, visible by public only, etc...)
			}
			$filters["type"]['$in'] = [Person::DEVISER];

			$res = empty($filters) ? Person::find() : Person::find()->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$_deviser = $this->getJsonFromRequest("person");

			if ($_deviser["short_id"] === "new") {
				$_deviser["short_id"] = (new Person())->genValidID(7);
			}

			/* @var $deviser \app\models\Person */
			$deviser = Person::findOne(["short_id" => $_deviser["short_id"]]);
			$deviser->setAttributes($_deviser, false);
			$deviser->personalInfoMapping->load($_deviser, "personal_info");
			$deviser->save(false);

			$res = $deviser;
		} else if ($request->isDelete) {
			$deviser = $this->getJsonFromRequest("person");

			/* @var $deviser \app\models\Person */
			$deviser = Person::findOne(["short_id" => $deviser["short_id"]]);
			$deviser->delete();
		}

		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_ADMIN);
		return $res;
	}

	public function actionPersons($filters = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ['short_id']);
				//TODO: If not admin, force some fields (enabled only, visible by public only, etc...)
			}

			$res = empty($filters) ? Person::find() : Person::find()->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$_deviser = $this->getJsonFromRequest("person");

			if ($_deviser["short_id"] === "new") {
				$deviser = new Person();
			} else {
				$deviser = Person::findOne(["short_id" => $_deviser["short_id"]]);
			}

			if (!$deviser) {
				throw new Exception("Cannot create/update person");
			}

			$deviser->setScenario(Person::SCENARIO_ADMIN);
			$deviser->setAttributes($_deviser, true);
			$deviser->save(false);

			if (isset($_deviser['change_email']) && !empty($_deviser['change_email'])) {
				$credentials = $deviser->credentials;
				$credentials['email'] = $_deviser['change_email'];
				$deviser->setAttribute('credentials', $credentials);
				$deviser->save(false);
			}

			$res = $deviser;

		} else if ($request->isDelete) {
			$deviser = $this->getJsonFromRequest("person");

			/* @var $deviser \app\models\Person */
			$deviser = Person::findOne(["short_id" => $deviser["short_id"]]);
			$deviser->delete();
		}

		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_ADMIN);
		return $res;
	}

	public function actionProducts($filters = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ["short_id"]);
				//TODO: If not admin, force some fields (enabled only, visible by public only, etc...)
			}

			$res = empty($filters) ? OldProduct::find() : OldProduct::find()->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$_product = $this->getJsonFromRequest("product");

			if ($_product["short_id"] === "new") {
				$_product["short_id"] = (new OldProduct())->genValidID(8);
			}

			/* @var $product \app\models\OldProduct */
			$product = OldProduct::findOne(["short_id" => $_product["short_id"]]);
			$product->setAttributes($_product, false);
			$product->save(false);

			$res = $product;
		} else if ($request->isDelete) {
			$product = $this->getJsonFromRequest("product");

			/* @var $product \app\models\OldProduct */
			$product = OldProduct::findOne(["short_id" => $product["short_id"]]);

			$product->deletePhotos();
			$product->delete();
		}

		OldProduct::setSerializeScenario(OldProduct::SERIALIZE_SCENARIO_ADMIN);
		return $res;
	}

	public function actionTags($filters = null, $fields = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];
			$fields = json_decode($fields, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ["short_id", "required", "type", "categories"]);

				//Force only enabled tags
				//$filters["enabled"] = true;
				//TODO: If not admin, force only enabled, etc...
			}
			$fields = array_merge(["_id" => 0], $fields);

			$res = empty($filters) ? Tag::find()->select($fields) : Tag::find()->select($fields)->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$_tag = $this->getJsonFromRequest("tag");

			if ($_tag["short_id"] === "new") {
				$_tag["short_id"] = (new Tag())->genValidID(5);
			}

			/* @var $tag \app\models\Tag */
			$tag = Tag::findOne(["short_id" => $_tag["short_id"]]);
			$tag->setAttributes($_tag, false);
			$tag->save(false);

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
			$filters = json_decode($filters, true) ?: [];
			$fields = json_decode($fields, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ["short_id", "categories"]);
				//TODO: If not admin, force some fields (enabled only, visible by public only, etc...)
				$filters["type"] = SizeChart::TODEVISE;
			}
			$fields = array_merge(["_id" => 0], $fields);

			$res = empty($filters) ? SizeChart::find()->select($fields) : SizeChart::find()->select($fields)->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$_sizechart = $this->getJsonFromRequest("sizechart");

			if ($_sizechart["short_id"] === "new") {
				$_sizechart["short_id"] = (new SizeChart())->genValidID(5);
			}

			/* @var $sizechart \app\models\SizeChart */
			$sizechart = SizeChart::findOne(["short_id" => $_sizechart["short_id"]]);
			$sizechart->setAttributes($_sizechart, false);
			$sizechart->save(false);

			$res = $sizechart;
		} else if ($request->isDelete) {
			$sizechart = $this->getJsonFromRequest("sizechart");

			/* @var $sizechart \app\models\SizeChart */
			$sizechart = SizeChart::findOne(["short_id" => $sizechart["short_id"]]);
			$sizechart->delete();
		}

		return $res;
	}

	public function actionCategories($filters = null, $fields = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];
			$fields = json_decode($fields, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ["short_id", "path"]);

				//TODO: If not admin, force only enabled, etc...
			}
			$fields = array_merge(["_id" => 0], $fields);

			$res = empty($filters) ? Category::find()->select($fields) : Category::find()->select($fields)->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$node = $this->getJsonFromRequest("category");

			if ($node["short_id"] !== "new") {
				$category = Category::findOne(["short_id" => $node["short_id"]]);
			} else {
				$category = new Category();
				$node["short_id"] = null;
			}

			/* @var $category \app\models\Category */
			$category->setAttributes($node, false);
			$category->name = array_replace_recursive($category->name, $node["name"]);
			$category->save(false);

			$res = $category;
		} else if ($request->isDelete) {
			$node = $this->getJsonFromRequest("category");

			/* @var $category \app\models\Category */
			$category = Category::findOne(["short_id" => $node["short_id"]]);
			$category->delete();
		}

		Category::setSerializeScenario(Category::SERIALIZE_SCENARIO_ADMIN);
		return $res;
	}


	public function actionFaq($filters = null, $fields = null){
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];
			$fields = json_decode($fields, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ["short_id", "path"]);

				//TODO: If not admin, force only enabled, etc...
			}
			$fields = array_merge(["_id" => 0], $fields);


			$res = empty($filters) ? Faq::find()->select($fields) : Faq::find()->select($fields)->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$node = $this->getJsonFromRequest("category");

			if ($node["short_id"] === "new") {
				$node["short_id"] = (new Faq())->genValidID(5);
			}

			/* @var $category \app\models\Category */
			$category = Faq::findOne(["short_id" => $node["short_id"]]);
			$category->setAttributes($node, false);
			//$category->title = array_replace_recursive($category->title, $node["title"]);
			$category->save(false);

			$res = $category;
		} else if ($request->isDelete) {
			$node = $this->getJsonFromRequest("category");

			/* @var $category \app\models\Category */
			$category = Faq::findOne(["short_id" => $node["short_id"]]);
			$category->delete();
		}

		Faq::setSerializeScenario(Faq::SERIALIZE_SCENARIO_ADMIN);
		return $res;

	}

	public function actionFaqs($filters = null, $fields = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];
			$fields = json_decode($fields, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ["short_id", "path"]);

				//TODO: If not admin, force only enabled, etc...
			}
			$fields = array_merge(["_id" => 0], $fields);

			$res = empty($filters) ? Faq::find()->select($fields) : Faq::find()->select($fields)->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$node = $this->getJsonFromRequest("category");

			if ($node["short_id"] === "new") {
				$node["short_id"] = (new Faq())->genValidID(5);
			}

			/* @var $faq \app\models\Category */
			$faq = Faq::findOne(["short_id" => $node["short_id"]]);
			$faq->setAttributes($node, false);
			$faq->title = array_replace_recursive($faq->title, $node["title"]);
			$faq->save(false);

			$res = $faq;
		} else if ($request->isDelete) {
			$node = $this->getJsonFromRequest("category");

			if ($node['path'] != '/') {
				$parts = explode('_', $node['short_id']);
				$faq_id = $parts[0];
				$faq_subid = $parts[1];

				/* @var $faq \app\models\Faq */
				$faq = Faq::findOne(["short_id"  => $faq_id]);
				if (isset($faq->faqs[$faq_subid])) {
					// Remove item
					$faqs = $faq->faqs;
					unset($faqs[$faq_subid]);
					$faq->setAttribute('faqs', $faqs);
				}
				$faq->save(false);
			} else {

				/* @var $faq \app\models\Faq */
				$faq = Faq::findOne(["short_id" => $node["short_id"]]);
				$faq->delete();
			}
		}

		Faq::setSerializeScenario(Faq::SERIALIZE_SCENARIO_ADMIN);
		return $res;
	}



	public function actionTerm($filters = null, $fields = null){
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];
			$fields = json_decode($fields, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ["short_id", "path"]);

				//TODO: If not admin, force only enabled, etc...
			}
			$fields = array_merge(["_id" => 0], $fields);


			$res = empty($filters) ? Term::find()->select($fields) : Term::find()->select($fields)->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$node = $this->getJsonFromRequest("category");

			if ($node["short_id"] === "new") {
				$node["short_id"] = (new Term())->genValidID(5);
			}

			/* @var $category \app\models\Category */
			$category = Term::findOne(["short_id" => $node["short_id"]]);
			$category->setAttributes($node, false);
			//$category->title = array_replace_recursive($category->title, $node["title"]);
			$category->save(false);

			$res = $category;
		} else if ($request->isDelete) {
			$node = $this->getJsonFromRequest("category");

			/* @var $category \app\models\Category */
			$category = Term::findOne(["short_id" => $node["short_id"]]);
			$category->delete();
		}

		Term::setSerializeScenario(Term::SERIALIZE_SCENARIO_ADMIN);
		return $res;

	}

	public function actionTerms($filters = null, $fields = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];
			$fields = json_decode($fields, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ["short_id", "path"]);

				//TODO: If not admin, force only enabled, etc...
			}
			$fields = array_merge(["_id" => 0], $fields);

			$res = empty($filters) ? Term::find()->select($fields) : Term::find()->select($fields)->where($filters);

			$res = $res->asArray()->all();
		} else if ($request->isPost) {
			$node = $this->getJsonFromRequest("category");

			if ($node["short_id"] === "new") {
				$node["short_id"] = (new Term())->genValidID(5);
			}

			/* @var $category \app\models\Category */
			$category = Term::findOne(["short_id" => $node["short_id"]]);
			$category->setAttributes($node, false);
			$category->title = array_replace_recursive($category->title, $node["title"]);
			$category->save(false);

			$res = $category;
		} else if ($request->isDelete) {
			$node = $this->getJsonFromRequest("category");

			/* @var $category \app\models\Category */
			$category = Term::findOne(["short_id" => $node["short_id"]]);
			$category->delete();
		}

		Term::setSerializeScenario(Term::SERIALIZE_SCENARIO_ADMIN);
		return $res;
	}

	public function actionPaymentErrors($filters = null) {
		$request = Yii::$app->getRequest();
		$res = null;

		if ($request->isGet) {
			$filters = json_decode($filters, true) ?: [];

			if (!empty($filters)) {
				$filters = Utils::removeAllExcept($filters, ['short_id']);
				//TODO: If not admin, force some fields (enabled only, visible by public only, etc...)
			}

			if(!empty($filters)) {
				$res = PaymentErrors::find()->where($filters)->one();
			}

			if(!empty($res)) {
				try
				{
					Stripe::setApiKey(Yii::$app->params['stripe_secret_key']);
					$person = Person::find()->where(['short_id' => $res->person_id])->one();

					\Stripe\Account::retrieve($person->settingsMapping->stripeInfoMapping->stripe_user_id);

					// Make the transfer to the user who earn the amount
					$amount = $res->amount_earned;

					$transfer = \Stripe\Transfer::create(array(
						"amount" => round($amount*100, 0),
						"currency" => "eur",
						"destination" => $person->settingsMapping->stripeInfoMapping->stripe_user_id,
						"transfer_group" => $res->order_id."-".$res->pack_id,
						"metadata" => [
							"description" => "Order Nº " . $res->order_id . "/" . $res->pack_id,
							"order_id" => $res->order_id,
							"pack_id" => $res->pack_id,
							"person_id" => $person->short_id,
							],
					));
					$res->error_type_id = 'ok';
					$res->error_type_description = 'ok';
					
					$collection = Yii::$app->mongodb->getCollection('payment_errors');
					$collection->update(
						[
							'short_id' => $res->short_id
						],
						[
							'error_type_id' => 'ok',
							'error_type_description' => 'ok',
						]
					);
				}
				catch (\Exception $e) {
					// LOG PaymentErrors
					$collection = Yii::$app->mongodb->getCollection('payment_errors');
					$collection->update(
						[
							'short_id' => $res->short_id
						],
						[
							'error_type_id' => $e->getJsonBody()['error']['code'],
							'error_type_description' => $e->getMessage(),
						]
					);
				}
			}

		} else if ($request->isDelete) {
			$payment_error = $this->getJsonFromRequest("payment_error");
			$payment_error = PaymentErrors::findOne(["short_id" => $payment_error["short_id"]]);
			$payment_error->delete();
		}

		PaymentErrors::setSerializeScenario(PaymentErrors::SERIALIZE_SCENARIO_ADMIN);
		return $res;
	}

}
