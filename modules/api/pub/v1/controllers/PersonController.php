<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Invitation;
use app\models\Person;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class PersonController extends AppPublicController
{

	/**
	 * Create a new Person account
	 *
	 * @throws BadRequestHttpException
	 */
	public function actionCreate()
	{

		$person = new Person();

		$invitation_id = Yii::$app->request->post("uuid");
		/** @var Invitation $invitation */
		$invitation = Invitation::findOneSerialized($invitation_id);

		if (!$invitation) {
			throw new NotFoundHttpException(Yii::t("app/api", "Invitation not found"));
		}

		if (!$invitation->canUse()) {
			throw new BadRequestHttpException(Yii::t("app/api", "Invalid invitation"));
		}

		if ($invitation->email != Yii::$app->request->post('email')) {
			throw new BadRequestHttpException(Yii::t("app/api", "The invitation is for another email account"));
		}

		$person->setScenario($this->getScenarioFromRequest($person));
		$person->load(Yii::$app->request->post(), '');

		$person->credentials = ["email" => $invitation->email];
		$person->setPassword(Yii::$app->request->post("password"));

		// Load personal info directly to subdocument
		$person->personalInfoMapping->load(Yii::$app->request->post(), '');
		// Refresh properties from the embed
		$person->refreshFromEmbedded();

		if ($person->validate()) {
			$person->save();

			// relate invitation and new person
			$invitation->person_id = $person->_id;

			// indicate that invitation has been used
			$invitation->setAsUsed()->save();

			// return information needed to client side
			Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);

			return $person;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $person->errors];
		}
	}

	/**
	 * Get validation scenario from request param
	 *
	 * @param Person $deviser
	 * @return string
	 * @throws BadRequestHttpException
	 */
	private function getScenarioFromRequest(Person $deviser)
	{
		$type = Yii::$app->request->post('type');

		if ($type == Person::DEVISER || in_array(Person::DEVISER, $type)) {
			return Person::SCENARIO_DEVISER_CREATE_DRAFT;
		}

		if ($type == Person::INFLUENCER || in_array(Person::INFLUENCER, $type)) {
			return Person::SCENARIO_INFLUENCER_CREATE_DRAFT;
		}

		throw new BadRequestHttpException("Invalid type");
	}
}