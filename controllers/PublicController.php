<?php

namespace app\controllers;

use app\helpers\CController;
use app\helpers\ModelUtils;
use app\helpers\Utils;
use app\models\Banner;
use app\models\Become;
use app\models\Box;
use app\models\Category;
use app\models\ContactForm;
use app\models\Country;
use app\models\Faq;
use app\models\Invitation;
use app\models\Login;
use app\models\OldProduct;
use app\models\Person;
use app\models\Product;
use app\models\Story;
use app\models\Tag;
use app\models\Term;
use Yii;
use yii\base\DynamicModel;
use yii\data\ArrayDataProvider;
use yii\filters\AccessControl;
use yii\mongodb\ActiveQuery;
use yii\web\Response;

class PublicController extends CController
{
	public $defaultAction = "index";

	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['login', 'authentication-required', 'logout', 'checkout',],
				'rules' => [
					[
						'actions' => ['login', 'authentication-required'],
						'allow' => true,
						'roles' => ['?'],
					],
					[
						'actions' => ['login', 'authentication-required'],
						'allow' => false,
						'roles' => ['@'],
						'denyCallback' => function ($rule, $action) {
							return $this->goHome();
						}
					],
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
					[
						'actions' => ['checkout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
		];
	}

	public function actionError()
	{
		$exception = Yii::$app->errorHandler->exception;

		if ($exception !== null) {

			$this->view->params['show_header'] = false;
			$this->view->params['show_footer'] = false;

			$this->layout = '/desktop/public-2.php';

			return $this->render("error", [
				'name' => '',
				'message' => $exception->getMessage(),
			]);
		}
	}

	public function actionIndex()
	{
		return $this->mainPage();
	}

	public function actionCategoryB($slug, $category_id)
	{
		return $this->mainPage($slug, $category_id);
	}

	protected function mainPage($slug = null, $category_id = null)
	{
		Banner::setSerializeScenario(Banner::SERIALIZE_SCENARIO_PUBLIC);

		if ($category_id) {

			$category = Category::findOneSerialized($category_id);
			$categoryShortIds = $category->getShortIds();

			$banners = Banner::findSerialized(
				[
					'type' => Banner::BANNER_TYPE_CAROUSEL,
					'category_id' => $category_id,
				]
			);

			if (empty($banners)) {
				// If there is no banners for the current category, we find in the parent categories

				$banners = Banner::findSerialized(
					[
						'type' => Banner::BANNER_TYPE_CAROUSEL,
						'category_id' => $category->getAncestorIds(),
					]
				);
			}

			$homeBanners = [];

		} else {

			$category = null;
			$categoryShortIds = [];

			$banners = Banner::findSerialized(
				[
					'type' => Banner::BANNER_TYPE_CAROUSEL,
					'category_id' => null,
				]
			);

			$homeBanners = Banner::findSerialized(
				[
					'type' => Banner::BANNER_TYPE_HOME_BANNER,
					'category_id' => null,
				]
			);
		}

		// Devisers
		$devisers = Person::getRandomDevisers(21, $categoryShortIds);

		// Influencers
		$influencers = Person::getRandomInfluencers(21, $categoryShortIds);

		// Boxes
		$boxes = Box::getRandomBoxes(8, null, true);

		// Stories
		$stories = Story::getRandomStories(3);

		// Works
		$works = Product::getRandomWorks(48, $categoryShortIds);
		$htmlWorks = $this->renderPartial('more-works', [
			'total' => 48,
			'works' => $works,
		]);

		if ($devisers) {
			$devisersCarousel = $this->renderPartial('profiles-carousel', [
				'persons' => $devisers,
				'id' => 'devisers',
			]);
		} else {
			$devisersCarousel = null;
		}

		if ($influencers) {
			$influencersCarousel = $this->renderPartial('profiles-carousel', [
				'persons' => $influencers,
				'id' => 'influencers',
			]);
		} else {
			$influencersCarousel = null;
		}

		$this->layout = '/desktop/public-2.php';
		$this->view->params['selectedCategory'] = isset($category) ? $category : null;

		return $this->render("index-2", [
			'banners' => $banners,
			'homeBanners' => $homeBanners,
			'devisersCarousel' => $devisersCarousel,
			'influencersCarousel' => $influencersCarousel,
			'works' => $works,
			'htmlWorks' => $htmlWorks,
			"category" => $category,
			"category_id" => $category_id,
			'boxes' => $boxes,
			'stories' => $stories,
		]);
	}

	public function actionMoreWorks()
	{
		// show only fields needed in this scenario
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);

		$category_id = Yii::$app->request->get('category_id', null);

		if ($category_id) {
			$category = Category::findOneSerialized($category_id);
			/* @var Category $category */
			$categoryShortIds = $category->getShortIds();
		} else {
			$categoryShortIds = [];
		}

		$works = Product::getRandomWorks(48, $categoryShortIds);

		$this->layout = '/desktop/empty-layout.php';

		$html = $this->renderPartial("more-works", [
			'works' => $works,
		]);

		return json_encode([
			'html' => $html,
			"category_id" => $category_id,
		]);
	}

	public function actionBecomeDeviser()
	{
		$this->layout = '/desktop/public-2.php';

		return $this->render("become-deviser");
	}

	public function actionCreateDeviserAccount()
	{
		$invitationId = Yii::$app->request->get("uuid");
		$actionId = Yii::$app->request->get("action");
		/** @var Invitation $invitation */
		$invitation = Invitation::findByEmailAction($actionId);

		if ($invitation->uuid != $invitationId) {
			$invitation = null;
		}

		$this->view->params['show_header'] = false;
		$this->view->params['show_footer'] = false;

		$this->layout = '/desktop/public-2.php';

		return $this->render("create-deviser-account", ["invitation" => $invitation]);
	}

	public function actionBecomeInfluencer()
	{
		$this->layout = '/desktop/public-2.php';

		return $this->render("become-influencer");
	}

	public function actionCreateInfluencerAccount()
	{
		$invitationId = Yii::$app->request->get("uuid");
		$actionId = Yii::$app->request->get("action");
		/** @var Invitation $invitation */
		$invitation = Invitation::findByEmailAction($actionId);

		if ($invitation->uuid != $invitationId) {
			$invitation = null;
		}

		$this->view->params['show_header'] = false;
		$this->view->params['show_footer'] = false;

		$this->layout = '/desktop/public-2.php';

		return $this->render("create-influencer-account", ["invitation" => $invitation]);
	}

	public function actionSignup()
	{
		$this->view->params['show_header'] = false;
		$this->view->params['show_footer'] = false;

		$this->layout = '/desktop/public-2.php';

		return $this->render("signup", []);
	}

	public function actionCart()
	{
		$this->layout = '/desktop/public-2.php';

		return $this->render("cart", []);
	}

	public function actionCheckout()
	{
		$person = Yii::$app->user->identity;
		/* @var Person $person */

		if (!$person->isCompletedProfile()) {
			$this->redirect($person->getCompleteProfileLink());
		} else {
			if ($person->isDeviser() || $person->isInfluencer()) {
				if (!$person->isPublic()) {
					$this->redirect($person->getPersonNotPublicLink());
				}
			}
		}

		$this->layout = '/desktop/public-2.php';

		return $this->render("checkout", [
			'person' => Yii::$app->user->identity,
		]);
	}

	public function actionAboutUs()
	{
		$this->layout = '/desktop/public-2.php';

		return $this->render("about-us");
	}

	public function actionCookies()
	{
		$this->layout = '/desktop/public-2.php';

		return $this->render("legal-page", [
			'title' => Yii::t('app/cookies', 'TITLE'),
			'text' => Yii::t('app/cookies', 'TEXT'),
		]);
	}

	public function actionTerms()
	{
		$this->layout = '/desktop/public-2.php';

		return $this->render("legal-page", [
			'title' => Yii::t('app/terms', 'TITLE'),
			'text' => Yii::t('app/terms', 'TEXT'),
		]);
	}

	public function actionPrivacy()
	{
		$this->layout = '/desktop/public-2.php';

		return $this->render("legal-page", [
			'title' => Yii::t('app/privacy', 'TITLE'),
			'text' => Yii::t('app/privacy', 'TEXT'),
		]);
	}

	public function actionElements()
	{
		$this->layout = '/desktop/empty-layout.php';

		return $this->render("elements");
	}

	/**
	 * @return string
	 */
	public function actionContact()
	{
//		$dropdown_members = ['a' => 'ORDERS'];

		$groupOfFaqs = Faq::find()
			->asArray()->all();

		$faq = $groupOfFaqs[0]; //select first group

		Utils::l_collection($faq['faqs'], "question");
		Utils::l_collection($faq['faqs'], "answer");
		$faq['title'] = Utils::l($faq['title']);

		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post())) {

			// send email
			if ($model->contact(Yii::$app->params['admin_email'])) {
				$email = 'ok';
			} else {
				$email = 'error';
			}
		} else {
			$email = false;
		}

		$this->layout = '/desktop/public-2.php';

		return $this->render("contact", [
			'test' => $model->hasErrors(),
			'model' => $model,
//			'dropdown_members' => $dropdown_members,
			'faqs' => $faq['faqs'],
			'email' => $email,
		]);

	}

	/**
	 * @deprecated
	 * @return string
	 */
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

		return $this->render("_faq", [
			'test' => 'this is a test text for faq',
			'groupOfFaqs' => $groupOfFaqs
		]);
	}

	/**
	 * @deprecated
	 * @return string
	 */
	public function actionTermsOld()
	{
		$lang = Yii::$app->language;

		$groupOfTerms = Term::find()
			->asArray()->all();

		foreach ($groupOfTerms as $key => &$oneTerm) {
			Utils::l_collection($oneTerm['terms'], "question");
			Utils::l_collection($oneTerm['terms'], "answer");
			$oneTerm['title'] = Utils::l($oneTerm['title']);
		}

		return $this->render("_terms", [
			'test' => 'this is a test text for term',
			'groupOfTerms' => $groupOfTerms
		]);
	}

	/**
	 * @deprecated
	 * @return string
	 */
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

		return $this->render("_become", ['model' => $model, "showCheckEmail" => $showCheckEmail]);
	}

	/**
	 * @deprecated Was used only for demo
	 * @return array
	 */
	private function getDevisersFilterForDemo()
	{
		return [
			"slug" => [
				"phuong-my",
				"cinco-jotas",
				"kim-kwang",
				"chris-herbert",
				"vaskolg-vaskolg",
				"me-mover",
				"andrea-narchik",
				"cecilia-lundqvist",
				"monique-vansteen",
				"skatetek-skateboards",
				"lara-quint",
				"sk8-shades",
				"charbonize-charbonize",
				"neemic-neemic",
				"josep-moncada",
				"pocket-london",
				"ritot-michaelmedvid",
				"taeseok-kang",
				"ryan-andvaleriewiens",
				"niza-huang",
			]
		];
	}

	/**
	 * @deprecated Was used only for demo
	 * @return array
	 */
	private function getProductsFilterForDemo($limit = 60)
	{
		// selected products ids
		$selectedIds = [
			"1930f733",
			"568d60a7",
			"3cd57ca8",
			"76c5f330",
			"8a6dc1b2",
			"629bd454",
			"6a238916",
			"08b8fcb2",
			"1b704ae3",
			"280bb083",
			"5433f112",
			"4b53da6e",
			"833db559",
			"9a974261",
			"56cab3b7",
			"5da7e84e",
			"97324ed3",
			"8de89f30",
			"cde8a2ae",
			"2dd99092",
			"36323a98",
			"df2d9f4a",
			"02a80a47",
			"9c1ea809",
			"69e3827e",
			"601efeb5",
			"737d1a3a",
			"b50de546",
			"0a8aaa96",
			"b6dde285",
			"fcaa3c12",
			"b843a5d3",
			"a4404eda",
			"925d5bbd",
			"df58c170",
			"c063fb0f",
			"2820f40a",
			"31e1c80c",
			"853890bd",
			"ddb5df89",
			"b01fb976",
			"17795b12",
			"d1ed11c7",
			"d9ac3c09",
			"74386045",
			"214caaf0",
			"69d34604",
			"d8325fb7",
			"596ba29e",
			"a39ae909",
			"3867d0d0",
			"2d3dce66",
			"f52e8c21",
			"ec4232e4",
			"fe5ce5e5",
			"906e8023",
			"79628d14",
			"a9fbccf9",
			"cee1b8a8",
			"d0bf9730",
		];

		// other random products ids to complete the grid
		$query = new ActiveQuery(OldProduct::className());
		$query->select(["short_id"]);
		$query->where(["not in", "short_id", $selectedIds]);
		// filter products not saved properly, without name, and other attributes
		$query->andWhere(["<>", "name", []]);
		$query->limit($limit - count($selectedIds));
		$query->offset(rand(1, 100));
		$products = $query->all();
		$otherIds = [];
		foreach ($products as $product) {
			/** @var OldProduct $product */
			$otherIds[] = $product->short_id;
		}

		return ["short_id" => array_merge($selectedIds, $otherIds)];

	}

	/**
	 * @deprecated
	 * @return string
	 */
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
							"short_id" => 1,
							"media" => 1,
							"slug" => 1,
							"deviser_id" => 1,
							"categories" => 1
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
			'name' => Yii::t("app/public", 'ALL_CATEGORIES')
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
					'short_id' => ['$gt' => '']
					// We get here only with the 'all' (special) category, that's why we use a dummy filter to match everything.
				];
			}
			$tmp = Yii::$app->mongodb->getCollection('product')
				->aggregate(
					[
						'$project' => [
							"short_id" => 1,
							"slug" => 1,
							"categories" => 1,
							"name" => 1,
							"media" => 1
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

		return $this->render("_index", [
			'banners' => $banners,
			'devisers' => $devisers,
			'categories' => $categories
		]);
	}

	/**
	 * @deprecated
	 *
	 * @param $category_id
	 * @param $slug
	 *
	 * @return string
	 */
	public function actionCategory($category_id, $slug)
	{

		$tmp = Yii::$app->mongodb->getCollection('product')
			->aggregate(
				[
					'$project' => [
						"short_id" => 1,
						"slug" => 1,
						"categories" => 1,
						"name" => 1,
						"media" => 1
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

		return $this->render("_category", [
			'products' => $products
		]);
	}

	/**
	 * @deprecated
	 *
	 * @param $category_id
	 * @param $product_id
	 * @param $slug
	 *
	 * @return string
	 */
	public function actionProduct($category_id, $product_id, $slug)
	{

		$product = OldProduct::find()
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
						"short_id" => 1,
						"slug" => 1,
						"categories" => 1,
						"name" => 1,
						"media" => 1
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
		$tmp = OldProduct::find()->where(['deviser_id' => $product['deviser_id']])->asArray()->all();

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

		return $this->render("_product", [
			'product' => $product,
			'other_works' => $other_works,
			'deviser' => $deviser,
			'tags' => $tags,
			'category_id' => $category_id,
			'product_id' => $product_id,
			'slug' => $slug
		]);
	}

	/**
	 * @deprecated
	 *
	 * @param $deviser_id
	 * @param $slug
	 *
	 * @return string
	 */
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
		$deviser['img_header'] = ModelUtils::getPersonHeader($deviser);

		$tmp = OldProduct::find()->select(["_id" => 0])->where(["deviser_id" => $deviser_id])->asArray()->all();

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

		return $this->render("_deviser", [
			'deviser' => $deviser,
			'works' => $works
		]);
	}

	/**
	 * @deprecated
	 *
	 * @return array|string
	 */
	public function actionCartOld()
	{
		//Manage ajax query an return feedback
		if (Yii::$app->request->isAjax) {
			Yii::$app->response->format = Response::FORMAT_JSON;

			$res = [
				'body' => date('Y-m-d H:i:s'),
				'success' => true,
			];

			return $res;
		}

		//Show cart view
		return $this->render("_cart", [
			'test' => 'this is a test text'
		]);
	}

	public function actionCreateCountryPaths()
	{
		$countries = Country::findSerialized();
		/* @var $countries Country[] */
		foreach ($countries as $country) {
			$country->path = Country::WORLD_WIDE . '/' . $country->continent . '/' . $country->country_code;
			$country->save(true, ['path']);
		}
	}

	public function actionLogin()
	{
		$model = new Login();
		$invalidLogin = false;
		if ($model->load(Yii::$app->request->post())) {
			if ($model->login()) {
				return $this->goBack();
			}
			$invalidLogin = true;
		}
		$this->layout = '/desktop/public-2.php';

		return $this->render("login-2", [
			'invalidLogin' => $invalidLogin
		]);
	}

	public function actionAuthenticationRequired()
	{
		$model = new Login();
		$invalidLogin = false;
		if ($model->load(Yii::$app->request->post())) {
			if ($model->login()) {
				return $this->goBack();
			}
			$invalidLogin = true;
		}
		$this->layout = '/desktop/public-2.php';

		return $this->render("authentication-required", [
			'invalidLogin' => $invalidLogin
		]);
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}
}