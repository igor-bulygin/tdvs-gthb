<?php
namespace app\controllers;

use app\helpers\CController;
use app\models\Box;
use app\models\Loved;
use app\models\Person;
use yii\filters\AccessControl;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

/**
 * Class ClientController
 * @package app\controllers
 * @deprecated
 */
class ClientController extends CController
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

	public function actionLoved($slug, $person_id)
	{
		$person = Person::findOneSerialized($person_id);

		if (!$person || !$person->isClient()) {
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

		if (!$person || !$person->isClient()) {
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

		if (!$person || !$person->isClient()) {
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
}
