<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Invitation;
use app\models\Person;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;
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
		$type = Yii::$app->request->post("type");

		switch ($type[0]) {
			case Person::CLIENT:
				$account_state = Person::ACCOUNT_STATE_ACTIVE;
				break;
			case Person::DEVISER:
			case Person::INFLUENCER:
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
				$account_state = Person::ACCOUNT_STATE_DRAFT;
				break;
			default:
				throw new Exception("Invalid person type");
		}

		$email = Yii::$app->request->post('email');
		$personExists = Person::findByEmail($email);
		if ($personExists) {
			throw new ConflictHttpException("Email ".$email." already in use");
		}

		$person = new Person();
		$person->type = $type;
		$person->account_state = $account_state;
		$person->setScenario($this->getScenarioFromRequest($person));
		$person->load(Yii::$app->request->post(), '');

		$person->credentials = ["email" => $email];
		$person->setPassword(Yii::$app->request->post("password"));

		// Load personal info directly to subdocument
		$person->personalInfoMapping->load(Yii::$app->request->post(), '');
		// Refresh properties from the embed
		$person->refreshFromEmbedded();

		if ($person->validate()) {
			$person->save();

			if (isset($invitation)) {
				// relate invitation and new person
				$invitation->person_id = $person->_id;

				// indicate that invitation has been used
				$invitation->setAsUsed()->save();
			}

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

		if ($type == Person::CLIENT || in_array(Person::CLIENT, $type)) {
			return Person::SCENARIO_CLIENT_CREATE;
		}

		throw new BadRequestHttpException("Invalid person type");
	}
}