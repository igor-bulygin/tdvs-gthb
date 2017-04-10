<?php
namespace app\controllers;

use app\helpers\CController;
use app\helpers\Utils;
use app\models\Box;
use app\models\Category;
use app\models\Country;
use app\models\Loved;
use app\models\MetricType;
use app\models\Person;
use app\models\Product2;
use app\models\SizeChart;
use app\models\Tag;
use Yii;
use yii\filters\AccessControl;
use yii\helpers\Json;
use yii\mongodb\Collection;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class DeviserController extends CController
{
	public $defaultAction = "index";

	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'only' => [
								'about-edit', 'delete-product-photo', 'faq-edit', 'press-edit', 'store-edit', 'upload-header-photo', 'upload-product-photo', 'upload-profile-photo', 'videos-edit',
						],
						'rules' => [
								[
										'allow' => true,
										'roles' => ['@'],
								],
						],
				],
		];
	}

	public function actionIndex()
	{
		return $this->render("index");
	}

	public function actionStore($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		// categories of all products
		$categories = $person->getCategoriesOfProducts();
		/** @var Category $selectedCategory */
		$selectedCategory = $this->getCategoryById($categories, Yii::$app->request->get('category'));
		if (!isset($selectedCategory)) {
			$selectedCategory = (count($categories) > 0) ? $categories[0] : new Category();
		}
		/** @var Category $selectedSubcategory */
		$selectedSubcategory = $this->getSubcategoryById($selectedCategory->getDeviserSubcategories(), Yii::$app->request->get('subcategory'));
		if (!isset($selectedSubcategory)) {
			$selectedSubcategory = (count($selectedCategory->getDeviserSubcategories()) > 0) ? $selectedCategory->getDeviserSubcategories()[0] : new Category();
		}

		if ($person->isPersonEditable()) {
			$unpublishedWorks = Product2::findSerialized([
				"deviser_id" => $person_id,
				"product_state" => Product2::PRODUCT_STATE_DRAFT,
			]);
		} else {
			$unpublishedWorks = [];
		}

		// their products, for selected category
		$products = Product2::findSerialized([
			"deviser_id" => $person_id,
			"categories" => (empty($selectedSubcategory->short_id)) ? $selectedCategory->getShortIds() : $selectedSubcategory->getShortIds(),
			"product_state" => Product2::PRODUCT_STATE_ACTIVE,
		]);

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/store-view", [
			'person' => $person,
			'products' => $products,
			'categories' => $categories,
			'selectedCategory' => $selectedCategory,
			'selectedSubcategory' => $selectedSubcategory,
			'unpublishedWorks' => $unpublishedWorks,
		]);
	}

	public function actionStoreEdit($slug, $person_id)
	{
		// get the category object
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		// categories of all products
		$categories = $person->getCategoriesOfProducts();
		/** @var Category $selectedCategory */
		$selectedCategory = $this->getCategoryById($categories, Yii::$app->request->get('category'));
		if (!isset($selectedCategory)) {
			$selectedCategory = (count($categories) > 0) ? $categories[0] : new Category();
		}
		/** @var Category $selectedSubcategory */
		$selectedSubcategory = $this->getSubcategoryById($selectedCategory->getDeviserSubcategories(), Yii::$app->request->get('subcategory'));
		if (!isset($selectedSubcategory)) {
			$selectedSubcategory = (count($selectedCategory->getDeviserSubcategories()) > 0) ? $selectedCategory->getDeviserSubcategories()[0] : new Category();
		}

		$unpublishedWorks = Product2::findSerialized([
			"deviser_id" => $person_id,
			"product_state" => Product2::PRODUCT_STATE_DRAFT,
		]);

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/store-edit", [
			'person' => $person,
			'categories' => $categories,
			'selectedCategory' => $selectedCategory,
			'selectedSubcategory' => $selectedSubcategory,
			'unpublishedWorks' => $unpublishedWorks,
		]);
	}

	public function actionAbout($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE) {
			if ($person->isPersonEditable()) {
				$this->redirect($person->getAboutEditLink());
			} else {
				throw new NotFoundHttpException();
			}
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/about-view", [
			'person' => $person,
		]);
	}

	public function actionAboutEdit($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/about-edit", [
			'person' => $person,
		]);
	}

	public function actionPress($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/press-view", [
			'person' => $person,
			'press' => $person->press,
		]);
	}

	public function actionPressEdit($slug, $person_id)
	{
		// get the category object
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/press-edit", [
			'person' => $person,
		]);
	}

	public function actionVideos($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/videos-view", [
			'person' => $person,
			'videos' => $person->videosMapping,
		]);
	}

	public function actionVideosEdit($slug, $person_id)
	{
		// get the category object
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/videos-edit", [
			'person' => $person,
		]);
	}

	public function actionFaq($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/faq-view", [
			'person' => $person,
			'faq' => $person->faq,
		]);
	}

	public function actionFaqEdit($slug, $person_id)
	{
		// get the category object
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/faq-edit", [
			'person' => $person,
		]);
	}

	public function actionLoved($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$loveds = Loved::findSerialized(['person_id' => $person_id]);
		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/loved-view", [
			'person' => $person,
			'loveds' => $loveds,
		]);
	}

	public function actionBoxes($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$boxes = Box::findSerialized(['person_id' => $person_id]);
		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/boxes-view", [
			'person' => $person,
			'boxes' => $boxes,
		]);
	}

	public function actionBoxDetail($slug, $person_id, $box_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		Box::setSerializeScenario(Box::SERIALIZE_SCENARIO_PUBLIC);
		$box = Box::findOneSerialized($box_id);
		if (!$box) {
			throw new NotFoundHttpException();
		}

		if ($box->person_id != $person->short_id) {
			throw new ForbiddenHttpException();
		}

		$boxes = Box::getRandomBoxes(16, $box->short_id);

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/box-detail", [
			'person' => $person,
			'box' => $box,
			'moreBoxes' => $boxes,
		]);
	}

	/**
	 * Find a category from list by their short_id
	 *
	 * @param array $categories
	 * @param $category_id
	 * @return Category
	 */
	private function getCategoryById(array $categories, $category_id)
	{
		/** @var Category $category */
		foreach ($categories as $category) {
			if ($category->short_id == $category_id) {
				return $category;
			}
		}

		return null;
	}

	/**
	 * Find a subcategory from list by their short_id
	 *
	 * @param array $subcategories
	 * @param $category_id
	 * @return Category
	 */
	private function getSubcategoryById(array $subcategories, $category_id)
	{
		/** @var Category $subcategory */
		foreach ($subcategories as $subcategory) {
			if ($subcategory->short_id == $category_id) {
				return $subcategory;
			}
		}

		return null;
	}


	/*********************************************************************************************************/
	/******************************* deprecated methods after this line **************************************/
	/*********************************************************************************************************/

	/**
	 * @param $slug
	 * @return string
	 * @deprecated
	 */
	public function actionEditInfo($slug)
	{
		$countries = Country::find()
			->select(["_id" => 0])
			->asArray()
			->all();
		Utils::l_collection($countries, "country_name");

		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized(Yii::$app->request->get('deviser_id'))->toArray();
		$person["short_id"] = $person["id"];

		$categories = Category::find()
			->select(["_id" => 0])
			->where(["path" => "/"])
			->orderBy(["name.$this->lang" => -1])
			->asArray()
			->all();
		Utils::l_collection($categories, "name");

		return $this->render("edit-info", [
			"deviser" => $person,
			"slug" => $slug,
			'categories' => $categories,
			"countries" => $countries
		]);
	}

	/**
	 * @param $short_id
	 * @return string
	 * @deprecated
	 */
	public function actionEditWork($short_id)
	{
		$countries = Country::find()
			->select(["_id" => 0, "country_name.$this->lang", "country_name.$this->lang_en", "country_code", "continent"])
			->asArray()
			->all();

		$countries_lookup = [];
		foreach ($countries as $country) {
			$countries_lookup[$country["country_code"]] = Utils::l($country["country_name"]);
		}
		foreach (Country::CONTINENTS as $code => $continent) {
			$countries_lookup[$code] = Yii::t("app/admin", $continent);
		}

		$product = Product2::find()
			->select(["_id" => 0])
			->where(["short_id" => $short_id])
			->asArray()
			->all();

		$person = Person::find()
			->select(["_id" => 0])
			->where(["short_id" => $product[0]["deviser_id"]])
			->asArray()
			->one();

		$tags = Tag::find()
			->select(["_id" => 0, "short_id", "enabled", "n_options", "required", "stock_and_price", "type", "name.$this->lang", "name.$this->lang_en", "description.$this->lang", "description.$this->lang_en", "categories", "options"])
			->asArray()
			->all();
		Utils::l_collection($tags, "name");
		Utils::l_collection($tags, "description");
		foreach ($tags as $key => &$value) {
			Utils::l_collection($value['options'], "text");
		}

		$categories = Category::find()
			->select(["_id" => 0, "short_id", "path", "name.$this->lang", "name.$this->lang_en", "sizecharts", "prints"])
			->asArray()
			->all();
		Utils::l_collection($categories, "name");

		$sizechart = SizeChart::find()
			->select(["_id" => 0])
			->where(["type" => SizeChart::TODEVISE])
			->asArray()
			->all();
		Utils::l_collection($sizechart, "name");

		$deviser_sizecharts = SizeChart::find()
			->select(["_id" => 0])
			->where(["type" => SizeChart::DEVISER, "deviser_id" => $person["short_id"]])
			->asArray()
			->all();
		Utils::l_collection($deviser_sizecharts, "name");

		$mus = [
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::NONE]),
				"sub" => []
			],
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::SIZE]),
				"sub" => array_map(function ($x) {
					return ["text" => $x, "value" => $x];
				}, MetricType::UNITS[MetricType::SIZE])
			],
			[
				"text" => Yii::t("app/admin", MetricType::TXT[MetricType::WEIGHT]),
				"sub" => array_map(function ($x) {
					return ["text" => $x, "value" => $x];
				}, MetricType::UNITS[MetricType::WEIGHT])
			]
		];

		return $this->render("edit-work", [
			"deviser" => $person,
			"product" => $product[0],
			"tags" => $tags,
			'categories' => $categories,
			"countries" => $countries,
			"countries_lookup" => $countries_lookup,
			"sizecharts" => $sizechart,
			"deviser_sizecharts" => $deviser_sizecharts,
			"mus" => $mus
		]);
	}

	/**
	 * @param $slug
	 *
	 * @return Person
	 * @deprecated
	 */
	public function actionUploadHeaderPhoto($slug)
	{
		/* @var $person \app\models\Person */
		$person = Person::findOne(["slug" => $slug]);
		$deviser_path = Utils::join_paths(Yii::getAlias("@deviser"), $person->short_id);

		$res = $this->savePostedFile($deviser_path, ("header." . uniqid()));
		if ($res !== false) {
			//Delete the old header picture if it didn't get overridden by the new one
//			if (isset($person["media"]["header"]) && strcmp($person["media"]["header"], $res) !== 0) {
//				@unlink(Utils::join_paths($deviser_path, $person["media"]["header"]));
//			}

			$person->mediaFiles->header = $res;
			$person->save(false);

			Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_ADMIN);
			return $person;
		}
	}

	/**
	 * @param $slug
	 *
	 * @return Person
	 * @deprecated
	 */
	public function actionUploadProfilePhoto($slug)
	{
		/* @var $person \app\models\Person */
		$person = Person::findOne(["slug" => $slug]);
		$deviser_path = Utils::join_paths(Yii::getAlias("@deviser"), $person->short_id);

		$res = $this->savePostedFile($deviser_path, ("profile." . uniqid()));
		if ($res !== false) {
			//Delete the old profile picture if it didn't get overridden by the new one
//			if (isset($person["media"]["profile"]) && strcmp($person["media"]["profile"], $res) !== 0) {
//				@unlink(Utils::join_paths($deviser_path, $person["media"]["profile"]));
//			}

			$person->mediaFiles->profile = $res;

			$person->save(false);

			Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_ADMIN);
			return $person;
		}
	}

	/**
	 * @param $slug
	 * @param $short_id
	 *
	 * @return \app\models\Product|void
	 * @deprecated
	 */
	public function actionUploadProductPhoto($slug, $short_id)
	{
		/* @var $product \app\models\Product */
		$product = Product2::findOne(["short_id" => $short_id]);

		$data = Yii::$app->request->getBodyParam("data", null);
		if ($data === null) return;

		$data = Json::decode($data);
		$product_path = Utils::join_paths(Yii::getAlias("@product"), $short_id);

		//If we passed a name, make sure to override the existing file
		$data["name"] = $data["name"] === "" ? null : $data["name"];
		$res = $this->savePostedFile($product_path, $data["name"]);

		if ($res !== false) {
			$media = $product->media;
			$media["photos"][] = [
				"name" => $res,
				"tags" => $data["tags"]
			];
			$product->media = $media;
			$product->save();

			Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_ADMIN);
			return $product;
		}
	}

	/**
	 * @param $slug
	 * @param $short_id
	 *
	 * @return \app\models\Product
	 * @deprecated
	 */
	public function actionDeleteProductPhoto($slug, $short_id)
	{
		/* @var $product \app\models\Product */
		$product = Product2::findOne(["short_id" => $short_id]);
		$product_path = Utils::join_paths(Yii::getAlias("@product"), $product->short_id);
		$photo_name = $this->getJsonFromRequest("photo_name");

		@unlink(Utils::join_paths($product_path, $photo_name));

		$media = $product->media;
		$media["photos"] = array_values(array_filter($media["photos"], function ($photo) use ($photo_name) {
			return $photo["name"] !== $photo_name;
		}));
		$product->media = $media;
		$product->save();

		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_ADMIN);
		return $product;
	}

	/**
	 * Updates all devisers passwords to todevise1234
	 *
	 * @deprecated
	 * @throws \yii\mongodb\Exception
	 */
	public function actionUpdatePasswords()
	{
		ini_set('memory_limit', '2048M');
		set_time_limit(-1);

		/* @var Person[] $persons */
		$persons = Person::find()->where(
			[
				'type' => [Person::CLIENT],
			]
		)->all();
		foreach ($persons as $person) {
			$person->setPassword('todevise1234');

			// Update directly in low level, to avoid no desired behaviors of ActiveRecord
			/** @var Collection $collection */
			$collection = Yii::$app->mongodb->getCollection('person');
			$collection->update(
					[
							'short_id' => $person->short_id
					],
					[
							'credentials' => $person->credentials
					]
			);
		}
	}

}
