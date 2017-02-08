<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Person;
use Yii;
use yii\web\BadRequestHttpException;

class DeviserController extends AppPrivateController
{

	public function actionView()
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		/** @var Person $deviser */
		$deviser = $this->getPerson();

		return $deviser;
	}

	public function actionUpdate()
	{
		/** @var Person $deviser */
		$deviser = $this->getPerson();

		$newAccountState = Yii::$app->request->post('account_state');
		$this->checkDeviserAccountState($deviser, $newAccountState); // check for allowed new account state only

		$deviser->setScenario($this->getDetermineScenario($deviser)); // safe and required attributes are related with scenario
		if ($deviser->load(Yii::$app->request->post(), '') && $deviser->save()) {

			Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
			return $deviser;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $deviser->errors];
		}

	}

	/**
	 * Get validation scenario from request param
	 *
	 * @param Person $deviser
	 * @return string
	 * @throws BadRequestHttpException
	 */
	private function getDetermineScenario(Person $deviser)
	{
		// get scenario to use in validations, from request
		$scenario = Yii::$app->request->post('scenario', Person::SCENARIO_DEVISER_UPDATE_PROFILE);

		// check that is a valid scenario for this controller
		if (!in_array($scenario, [
//			Person::SCENARIO_DEVISER_CREATE_DRAFT,
//			Person::SCENARIO_DEVISER_UPDATE_DRAFT,
			Person::SCENARIO_DEVISER_UPDATE_PROFILE,
//			Person::SCENARIO_DEVISER_PUBLISH_PROFILE,
//			Person::SCENARIO_DEVISER_PRESS_UPDATE,
//			Person::SCENARIO_DEVISER_VIDEOS_UPDATE,
//			Person::SCENARIO_DEVISER_FAQ_UPDATE,
		])
		) {
			throw new BadRequestHttpException('Invalid scenario');
		}

		// can't change from "active" to "draft"
		if ($deviser->account_state == Person::ACCOUNT_STATE_ACTIVE) {
			// it is updating a active profile (or a profile that want to be active)
			$scenario = Person::SCENARIO_DEVISER_UPDATE_PROFILE;
		} else {
			// it is updating a draft profile
			$scenario = Person::SCENARIO_DEVISER_UPDATE_DRAFT;
		}

		return $scenario;
	}

	/**
	 * Logic for assign new deviser account state.
	 * Only allow change state to "active", otherwise, raise an exception
	 *
	 * @param Person $deviser
	 * @param $accountState
	 * @throws BadRequestHttpException
	 */
	private function checkDeviserAccountState(Person $deviser, $accountState)
	{
		if (!empty($accountState)) {
			// allowed new account state depends on current account state
			switch ($deviser->account_state) {
				case Person::ACCOUNT_STATE_DRAFT:
					if (!in_array($accountState, [Person::ACCOUNT_STATE_DRAFT, Person::ACCOUNT_STATE_ACTIVE])) {
						throw new BadRequestHttpException('Invalid account state');
					}
					break;
				case Person::ACCOUNT_STATE_ACTIVE:
					if ($accountState != Person::ACCOUNT_STATE_ACTIVE) {
						throw new BadRequestHttpException('Invalid account state');
					}
					break;
			}

			$deviser->account_state = $accountState;
		}
	}
}

