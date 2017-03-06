<?php
namespace app\controllers;

use app\helpers\CController;
use app\models\Box;
use app\models\Loved;
use app\models\Person;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class InfluencerController extends CController
{
	public $defaultAction = "index";

	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'only' => [
								'about-edit', 'delete-product-photo', 'faq-edit', 'press-edit', 'upload-header-photo', 'upload-product-photo', 'upload-profile-photo', 'videos-edit',
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

	public function actionAbout($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isInfluencer()) {
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
		return $this->render("about-view", [
			'person' => $person,
		]);
	}

	public function actionAboutEdit($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isInfluencer()) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("about-edit", [
			'person' => $person,
		]);
	}

	public function actionPress($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isInfluencer()) {
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
		return $this->render("press-view", [
			'person' => $person,
			'press' => $person->press,
		]);
	}

	public function actionPressEdit($slug, $person_id)
	{
		// get the category object
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isInfluencer()) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("press-edit", [
			'person' => $person,
		]);
	}

	public function actionVideos($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isInfluencer()) {
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
		return $this->render("videos-view", [
			'person' => $person,
			'videos' => $person->videosMapping,
		]);
	}

	public function actionVideosEdit($slug, $person_id)
	{
		// get the category object
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isInfluencer()) {
			throw new NotFoundHttpException();
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$this->layout = '/desktop/public-2.php';
		return $this->render("videos-edit", [
			'person' => $person,
		]);
	}

	public function actionLoved($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isInfluencer()) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE) {
			if ($person->isPersonEditable()) {
				$this->redirect($person->getAboutEditLink());
			} else {
				throw new NotFoundHttpException();
			}
		}

		$loveds = Loved::findSerialized(['person_id' => $person_id]);
		$this->layout = '/desktop/public-2.php';
		return $this->render("loved-view", [
			'person' => $person,
			'loveds' => $loveds,
		]);
	}

	public function actionBoxes($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isInfluencer()) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE) {
			if ($person->isPersonEditable()) {
				$this->redirect($person->getAboutEditLink());
			} else {
				throw new NotFoundHttpException();
			}
		}

		$boxes = Box::findSerialized(['person_id' => $person_id]);
		$this->layout = '/desktop/public-2.php';
		return $this->render("boxes-view", [
			'person' => $person,
			'boxes' => $boxes,
		]);
	}

	public function actionBoxDetail($slug, $person_id, $box_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isInfluencer()) {
			throw new NotFoundHttpException();
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE) {
			if ($person->isPersonEditable()) {
				$this->redirect($person->getAboutEditLink());
			} else {
				throw new NotFoundHttpException();
			}
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
		return $this->render("box-detail", [
			'person' => $person,
			'box' => $box,
			'moreBoxes' => $boxes,
		]);
	}
}
