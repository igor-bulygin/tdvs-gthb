<?php

namespace app\controllers;

use app\helpers\CActiveRecord;
use Yii;
use app\models\Tag;
use app\models\StaticText;
use yii\helpers\Url;
use app\models\Person;
use app\helpers\Utils;
use yii\mongodb\ActiveQuery;
use yii\mongodb\Query;
use yii\web\Controller;
use app\models\Product;
use app\models\Become;
use app\models\Category;
use app\models\ContactForm;
use yii\base\DynamicModel;
use yii\filters\VerbFilter;
use app\helpers\ModelUtils;
use app\helpers\CController;
use yii\filters\AccessControl;
use yii\data\ArrayDataProvider;
use yii\data\ActiveDataProvider;
use yii\base\ViewContextInterface;
use app\models\Faq;
use yii\web\Response;
use app\models\Term;

class PublicController extends CController
{
	public $defaultAction = "index";

	public function actionError()
	{
		//error_log("Error public", 4);
		//die();
	}

	public function actionIndexOld()
	{
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
						}, ModelUtils::getSubCategories($category['short_id']))
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

	public function actionIndex()
	{
		$banners = Utils::getBannerImages();

		// Devisers
		$query = new ActiveQuery(Person::className());
		// filter devisers related
		// TODO improve random devisers
		$query->limit(20)->offset(rand(1, 100));
		$devisers = $query->all();

		// Works
		$query = new ActiveQuery(Product::className());
		// TODO improve random works
		$query->limit(60)->offset(rand(1, 100));
//		$query->limit(150);
		$works = $query->all();

		$this->layout = '/desktop/public-2.php';
		return $this->render("index-2", [
			'banners' => $banners,
			'devisers' => [
				array_slice($devisers, 0, 4),
				array_slice($devisers, 4, 4),
				array_slice($devisers, 8, 4),
				array_slice($devisers, 12, 4),
				array_slice($devisers, 16, 4)
			],
			'works12' => array_slice($works, 0, 12),
			'works3' => array_slice($works, 12, 3),
			'moreWork' => [
				[
					"twelve" => array_slice($works, 15, 12),
					"three" => array_slice($works, 27, 3),
				],
				[
					"twelve" => array_slice($works, 30, 12),
					"three" => array_slice($works, 47, 3),
				],
				[
					"twelve" => array_slice($works, 45, 12),
					"three" => array_slice($works, 57, 3),
				],
			],
		]);
	}

	public function actionCategoryB($slug, $category_id)
	{
		$banners = Utils::getBannerImages($category_id);

//		$category_id = '1a23b'; // "Art"
//		$category_id = '4a2b4'; // "Fashion"
//		$category_id = '2r67s'; // "Decoration"
//		$category_id = '2p45q'; // "Industrial Design"
//		$category_id = '3f78g'; // "Jewelry"

		// get the category object
		$category = Category::findOne(["short_id" => $category_id]);
//		print_r(count($category->getShortIds()));

		// Devisers
		$query = new ActiveQuery(Person::className());
		// filter devisers related
		$query->where(['categories' => $category->getShortIds()]);
		// TODO improve random devisers
		$query->limit(4)->offset(rand(1, 8));
		$devisers = $query->all();

		// Works
		$query = new ActiveQuery(Product::className());
		$query->where(['categories' => $category->getShortIds()]);
		// TODO improve random works
		$query->limit(60)->offset(rand(1, 12));
		$works = $query->all();

		$categories = [];

		$this->layout = '/desktop/public-2.php';
		return $this->render("index-2", [
			'banners' => $banners,
			'devisers' => $devisers,
			'works12' => array_slice($works, 0, 12),
			'works3' => array_slice($works, 12, 3),
			'moreWork' => [
				[
					"twelve" => array_slice($works, 15, 12),
					"three" => array_slice($works, 27, 3),
				],
				[
					"twelve" => array_slice($works, 30, 12),
					"three" => array_slice($works, 47, 3),
				],
				[
					"twelve" => array_slice($works, 45, 12),
					"three" => array_slice($works, 57, 3),
				],
			],
			'categories' => $categories,
		]);

	}

	public function actionCategory($category_id, $slug)
	{

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
							}, ModelUtils::getSubCategories($category_id))
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

	public function actionProduct($category_id, $product_id, $slug)
	{

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

	public function actionProductB($slug, $product_id)
	{
		// get the category object
		$product = Product::findOne(["short_id" => $product_id]);

		$this->layout = '/desktop/public-2.php';
		return $this->render("product-2", [
			'product' => $product,
		]);

	}

	public function actionDeviser($deviser_id, $slug)
	{
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

	public function actionCart()
	{
		//Manage ajax query an return feedback
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			$res = array(
				'body' => date('Y-m-d H:i:s'),
				'success' => true,
			);

			return $res;
		}

		//Show cart view
		return $this->render("cart", [
			'test' => 'this is a test text'
		]);
	}

	public function actionFaq()
	{
		$lang = Yii::$app->language;

		// $answersAndQuestions = Faq::find()
		// 	->where([
		// 		"lang" => $lang
		// 	])
		// 	->asArray()->all();
		$groupOfFaqs = Faq::find()
			->asArray()->all();

		foreach ($groupOfFaqs as $key => &$oneFaq) {
			Utils::l_collection($oneFaq['faqs'], "question");
			Utils::l_collection($oneFaq['faqs'], "answer");
			$oneFaq['title'] = Utils::l($oneFaq['title']);
		}

		return $this->render("faq", [
			'test' => 'this is a test text for faq',
			'groupOfFaqs' => $groupOfFaqs
		]);
	}

	public function actionAbout()
	{

		return $this->render("about", [
			'test' => 'this is a test text for about'
		]);
	}


	public function actionTerms()
	{
		$lang = Yii::$app->language;

		$groupOfTerms = Term::find()
			->asArray()->all();

		foreach ($groupOfTerms as $key => &$oneTerm) {
			Utils::l_collection($oneTerm['terms'], "question");
			Utils::l_collection($oneTerm['terms'], "answer");
			$oneTerm['title'] = Utils::l($oneTerm['title']);
		}

		return $this->render("terms", [
			'test' => 'this is a test text for term',
			'groupOfTerms' => $groupOfTerms
		]);
	}

	public function actionContact()
	{
		$dropdown_members = ['a' => 'ORDERS'];
		$model = new ContactForm();

		$groupOfFaqs = Faq::find()
			->asArray()->all();

		$faq = $groupOfFaqs[0]; //select first group

		Utils::l_collection($faq['faqs'], "question");
		Utils::l_collection($faq['faqs'], "answer");
		$faq['title'] = Utils::l($faq['title']);

		if ($model->load(Yii::$app->request->post())) {
			return $this->render("contact", [
				'test' => $model->hasErrors(),
				'model' => $model,
				'dropdown_members' => $dropdown_members,
				'faqs' => $faq['faqs']
			]);
			//return $res;
			//return $this->redirect(['view', 'id' => $model->code]);
		} else {
			return $this->render("contact", [
				'test' => 'normal',
				'model' => $model,
				'dropdown_members' => $dropdown_members,
				'faqs' => $faq['faqs']
			]);

		}
	}

	public function actionBecome()
	{
		$model = new Become();
		//print_r(Yii::$app->request->post());
		//info@todevise.com, agrigoriu@todevise.com y jordioliu@todevise.com.
		$showCheckEmail = false;

		if ($model->load(Yii::$app->request->post())) {

			$post = Yii::$app->request->post()['Become'];

			Yii::$app->mailer->compose('request', [
				'post' => $post
			])
				->setFrom('no-reply@todevise.com')
				->setTo('info@todevise.com')
				->setSubject('Deviser request')
				->send();

			$showCheckEmail = true;

//				Yii::$app->mailer->compose('request',[
//					'post' => $post
//				])				->setFrom('no-reply@todevise.com')
//				->setTo('agrigoriu@todevise.com')
//				->setSubject('Deviser request')
//				->send();
//
//				Yii::$app->mailer->compose('request',[
//					'post' => $post
//				])				->setFrom('no-reply@todevise.com')
//				->setTo('jordioliu@todevise.com')
//				->setSubject('Deviser request')
//				->send();


			//return $res;
			//return $this->redirect(['view', 'id' => $model->code]);
		}

		return $this->render("become", ['model' => $model, "showCheckEmail" => $showCheckEmail]);
	}

}
