<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Person;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class PersonController extends AppPrivateController
{

	public function actionView($personId)
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);

		/** @var Person $person */
		$person = Person::findOne(["short_id" => $personId]);
		if (empty($person)) {
			throw new NotFoundHttpException('Person not found');
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		return $person;
	}

	public function actionUpdate($personId)
	{
		/** @var Person $person */
		$person = Person::findOne(["short_id" => $personId]);
		if (empty($person)) {
			throw new NotFoundHttpException('Person not found');
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$newAccountState = Yii::$app->request->post('account_state');
		$this->checkDeviserAccountState($person, $newAccountState); // check for allowed new account state only

		$person->setScenario($this->getScenarioFromRequest($person)); // safe and required attributes are related with scenario
		if ($person->load(Yii::$app->request->post(), '') && $person->validate(array_keys(Yii::$app->request->post()))) {

			$person->save(false);

			Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
			return $person;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return [
				"errors" => $person->errors,
			];
		}

	}

	public function actionUpdatePassword($personId)
	{
		/** @var Person $person */
		$person = Person::findOne(["short_id" => $personId]);
		if (empty($person)) {
			throw new NotFoundHttpException('Person not found');
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$oldPassword = Yii::$app->request->post('oldpassword');
		$newPassword = Yii::$app->request->post('newpassword');

		if (!empty($person->credentials['password']) && !$person->validatePassword($oldPassword)) {
			throw new BadRequestHttpException("Invalid old password");
		}
		$person->setPassword($newPassword);
		$person->save();

		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}

	/**
	 * Get validation scenario from request param
	 *
	 * @param Person $person
	 * @return string
	 * @throws BadRequestHttpException
	 */
	private function getScenarioFromRequest(Person $person)
	{
		$account_state = Yii::$app->request->post('account_state', $person->account_state);

		// can't change from "active" to "draft"
		if ($person->account_state == Person::ACCOUNT_STATE_ACTIVE || $account_state == Person::ACCOUNT_STATE_ACTIVE) {
			// it is updating a active profile (or a profile that want to be active)
			if ($person->isDeviser()) {
				$scenario = Person::SCENARIO_DEVISER_UPDATE_PROFILE;
			} elseif ($person->isInfluencer()) {
				$scenario = Person::SCENARIO_INFLUENCER_UPDATE_PROFILE;
			} elseif ($person->isClient()) {
				$scenario = Person::SCENARIO_CLIENT_UPDATE;
			} else{
				throw new Exception("Unknown person type");
			}
		} else {
			// it is updating a draft profile
			if ($person->isDeviser()) {
				$scenario = Person::SCENARIO_DEVISER_UPDATE_DRAFT;
			} elseif ($person->isInfluencer()) {
				$scenario = Person::SCENARIO_INFLUENCER_UPDATE_DRAFT;
			} elseif ($person->isClient()) {
				$scenario = Person::SCENARIO_CLIENT_UPDATE;
			} else {
				throw new Exception("Unknown person type");
			}
		}

		return $scenario;
	}

	/**
	 * Logic for assign new person account state.
	 * Only allow change state to "active", otherwise, raise an exception
	 *
	 * @param Person $person
	 * @param $accountState
	 * @throws BadRequestHttpException
	 */
	private function checkDeviserAccountState(Person $person, $accountState)
	{
		if (!empty($accountState)) {
			// allowed new account state depends on current account state
			switch ($person->account_state) {
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
		}
	}
}

