<?php
namespace app\controllers;

use app\helpers\CController;
use app\models\Box;
use app\models\Category;
use app\models\Loved;
use app\models\Person;
use app\models\Product;
use app\models\Story;
use Yii;
use yii\filters\AccessControl;
use yii\mongodb\Collection;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class PersonController extends CController
{
	public $defaultAction = "index";

	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => [
					'about-edit',
					'delete-product-photo',
					'faq-edit',
					'press-edit',
					'store-edit',
					'story-create',
					'story-edit',
					'upload-header-photo',
					'upload-product-photo',
					'upload-profile-photo',
					'videos-edit',
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

	public function actionCompleteProfile($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->isCompletedProfile()) {
			$this->redirect($person->getMainLink());
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/complete-profile", [
			'person' => $person,
		]);
	}

	public function actionDeviserNotPublic($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if (!$person->isCompletedProfile()) {
			$this->redirect($person->getCompleteProfileLink());
		}

		if ($person->isPublic()) {
			$this->redirect($person->getMainLink());
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/deviser-not-public", [
			'person' => $person,
		]);
	}

	public function actionStore($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		$this->checkProfileState($person);

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
			$unpublishedWorks = Product::findSerialized([
				"deviser_id" => $person_id,
				"product_state" => Product::PRODUCT_STATE_DRAFT,
			]);
		} else {
			$unpublishedWorks = [];
		}

		// their products, for selected category
		$products = Product::findSerialized([
			"deviser_id" => $person_id,
			"categories" => (empty($selectedSubcategory->short_id)) ? $selectedCategory->getShortIds() : $selectedSubcategory->getShortIds(),
			"product_state" => Product::PRODUCT_STATE_ACTIVE,
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

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

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

		$unpublishedWorks = Product::findSerialized([
			"deviser_id" => $person_id,
			"product_state" => Product::PRODUCT_STATE_DRAFT,
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

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new NotFoundHttpException();
		}

		$this->checkProfileState($person);

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/about-view", [
			'person' => $person,
		]);
	}

	public function actionAboutEdit($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/about-edit", [
			'person' => $person,
		]);
	}

	public function actionPress($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

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

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/press-edit", [
			'person' => $person,
		]);
	}

	public function actionVideos($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

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

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/videos-edit", [
			'person' => $person,
		]);
	}

	public function actionFaq($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

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

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/faq-edit", [
			'person' => $person,
		]);
	}

	public function actionLoved($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

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

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

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

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

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

	public function actionStories($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

		if ($person->isPersonEditable()) {
			$stories = Story::findSerialized(['person_id' => $person_id]);
		} else {
			$stories = Story::findSerialized(
				[
					'person_id' => $person_id,
					'story_state' => Story::STORY_STATE_ACTIVE,
				]
			);
		}
		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/stories-view", [
			'person' => $person,
			'stories' => $stories,
		]);
	}

	public function actionStoryCreate($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/story-create", [
			'person' => $person,
		]);
	}

	public function actionStoryEdit($slug, $person_id, $story_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

		Story::setSerializeScenario(Story::SERIALIZE_SCENARIO_OWNER);
		$story = Story::findOneSerialized($story_id);
		if (!$story) {
			throw new NotFoundHttpException();
		}

		if ($story->person_id != $person->short_id) {
			throw new ForbiddenHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/story-edit", [
			'story' => $story,
			'person' => $person,
		]);
	}

	public function actionStoryDetail($slug, $person_id, $story_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

		Story::setSerializeScenario(Story::SERIALIZE_SCENARIO_PUBLIC);
		$story = Story::findOneSerialized($story_id);
		if (!$story) {
			throw new NotFoundHttpException();
		}

		if ($story->person_id != $person->short_id) {
			throw new ForbiddenHttpException();
		}

		if ($story->story_state != Story::STORY_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/story-detail", [
			'person' => $person,
			'story' => $story,
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

	protected function checkProfileState(Person $person)
	{
		if (!$person->isCompletedProfile()) {
			$this->redirect($person->getCompleteProfileLink());
		} else {
			if ($person->isDeviser()) {
				if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE) {
					$this->redirect($person->getDeviserNotPublicLink());
				}
			}
		}
	}

	/**
	 * Updates ALL PASSWORDS to todevise1234
	 *
	 * @deprecated
	 * @throws \yii\mongodb\Exception
	 */
	public function actionUpdatePasswords()
	{
		ini_set('memory_limit', '2048M');
		set_time_limit(-1);

		/* @var Person[] $persons */
		$persons = Person::find()->all();
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
