<?php
namespace app\controllers;

use app\helpers\CController;
use app\helpers\InstagramHelper;
use app\models\Box;
use app\models\Category;
use app\models\Loved;
use app\models\Person;
use app\models\Product;
use app\models\Story;
use Yii;
use yii\filters\AccessControl;
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
					'timeline',
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

	public function actionIndex($slug, $person_id)
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}
		$this->redirect($person->getMainLink());
	}

	public function actionCompleteProfile($slug, $person_id)
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($person->isCompletedProfile()) {
			$this->redirect($person->getMainLink());
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->view->params['show_header'] = false;
		$this->view->params['show_footer'] = false;

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/complete-profile", [
			'person' => $person,
		]);
	}

	public function actionPersonNotPublic($slug, $person_id)
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
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

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		if ($person->isDeviser()) {
			$products = Product::findSerialized(['deviser_id' => $person->short_id]);
		} else {
			$products = [];
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/person-not-public", [
			'person' => $person,
			'products' => $products,
		]);
	}

	public function actionStore($slug, $person_id)
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getStoreLink(), 301);
		}

		$this->checkProfileState($person);

		$person->addVisit();

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
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getStoreEditLink(), 301);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getAboutLink(), 301);
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new NotFoundHttpException();
		}

		$this->checkProfileState($person);

		$person->addVisit();

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/about-view", [
			'person' => $person,
		]);
	}

	public function actionAboutEdit($slug, $person_id)
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getAboutEditLink(), 301);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getPressLink(), 301);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getPressEditLink(), 301);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getVideosLink(), 301);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getVideosEditLink(), 301);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getFaqLink(), 301);
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/faq-view", [
			'person' => $person,
		]);
	}

	public function actionFaqEdit($slug, $person_id)
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getFaqEditLink(), 301);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getLovedLink(), 301);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getBoxesLink(), 301);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
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

		if ($slug != $person->getSlug()) {
			$this->redirect($box->getViewLink(), 301);
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

	public function actionFollow($slug, $person_id)
	{
		return $this->mainFollow($slug, $person_id, 'follow');
	}

	public function actionFollowers($slug, $person_id)
	{
		return $this->mainFollow($slug, $person_id, 'followers');
	}

	private function mainFollow($slug, $person_id, $type)
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getSocialLink(), 301);
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

		if ($type == 'follow') {
			$persons = $person->getFollow();
		} else {
			$persons = $person->getFollowers();
		}

		$this->layout = '/desktop/public-2.php';

		return $this->render("@app/views/desktop/person/follow", [
			'person' => $person,
			'type' => $type,
			'persons' => $persons,
		]);
	}

	public function actionSocial($slug, $person_id, $type = 'follow')
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getSocialLink(), 301);
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

		$this->layout = '/desktop/public-2.php';

		return $this->render("@app/views/desktop/person/social-view", [
			'person' => $person,
		]);
	}

	public function actionSocialOld($slug, $person_id)
	{
		throw new NotFoundHttpException();
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getSocialLink(), 301);
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->checkProfileState($person);

		if (!empty($person->settingsMapping->instagram_info) && !empty($person->settingsMapping->instagram_info['access_token'])) {
			$connected = true;
			$accessToken = $person->settingsMapping->instagram_info['access_token'];

			$photos = Yii::$app->cache->get('instagram_'.$accessToken);
			if ($photos === false) {
				$photos = InstagramHelper::getUserSelfMedia($accessToken);
				Yii::$app->cache->set('instagram_'.$accessToken, $photos, 60);
			}
			if (isset($photos['meta']['code']) && $photos['meta']['code'] == 400) {
				$connected = false;
			}
		} else {
			$connected = false;
			$photos = [];
		}

		$this->layout = '/desktop/public-2.php';

		return $this->render("@app/views/desktop/person/social-view-old", [
			'person' => $person,
			'photos' => $photos,
			'connected' => $connected,
		]);
	}

	public function actionConnectInstagram($slug, $person_id) {

		// get the category object
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if (!$person->isInfluencer() && !$person->isDeviser()) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		if (!$person->isCompletedProfile()) {
			$this->redirect($person->getCompleteProfileLink());
		}

		\Yii::$app->session->set('person_id_instagram_connection', $person->short_id);

		$this->redirect(InstagramHelper::getAuthorizeUrl());
	}

	public function actionStories($slug, $person_id)
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getStoriesLink(), 301);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);

		if (!$person) {
			throw new NotFoundHttpException();
		}

		if ($slug != $person->getSlug()) {
			$this->redirect($person->getStoryCreateLink(), 301);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
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

		if ($slug != $person->getSlug()) {
			$this->redirect($story->getEditLink(), 301);
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
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
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

		if ($slug != $person->getSlug()) {
			$this->redirect($story->getViewLink(), 301);
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

	public function actionTimeline()
	{
		$this->layout = '/desktop/public-2.php';
		return $this->render("@app/views/desktop/person/timeline", [
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
			if ($person->isDeviser() || $person->isInfluencer()) {
				if (!$person->isPublic()) {
					$this->redirect($person->getPersonNotPublicLink());
				}
			}
		}
	}

}
