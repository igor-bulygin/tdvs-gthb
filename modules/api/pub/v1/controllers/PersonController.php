<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Invitation;
use app\models\Person;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;
use yii\web\NotFoundHttpException;
use yii\web\NotAcceptableHttpException;

class PersonController extends AppPublicController
{

	public function actionView($personId)
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);

		/** @var Person $person */
		$person = Person::findOneSerialized($personId);
		if (empty($person)) {
			throw new NotFoundHttpException('Person not found');
		}

		if ($person->account_state != Person::ACCOUNT_STATE_ACTIVE && !$person->isPersonEditable()) {
			Yii::$app->response->setStatusCode(204); // No content
			return null;
		}

		return $person;
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 0);
		$limit = ($limit < 0) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$persons = Person::findSerialized([
			"id" => Yii::$app->request->get("id"),
			"name" => Yii::$app->request->get("name"), // search only in name attribute
			"text" => Yii::$app->request->get("q"), // search in name, description, and more
			"type" => Yii::$app->request->get("type"),
			"categories" => Yii::$app->request->get("categories"),
			"countries" => Yii::$app->request->get("countries"),
			"account_state" => Person::ACCOUNT_STATE_ACTIVE,
			"limit" => $limit,
			"offset" => $offset,
			"order_col" => Yii::$app->request->get("order_col"),
			"order_dir" => Yii::$app->request->get("order_dir"),
		]);

		if (Yii::$app->request->get('rand', false)) {
			shuffle($persons);
		}

		return [
			"items" => $persons,
			"meta" => [
				"total_count" => Person::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}

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

				$parent_affiliate_id = trim(Yii::$app->request->post("parent_affiliate_id"));
				if(!empty($parent_affiliate_id)) {
					$parent_person = Person::findOne(['affiliate_id' => $parent_affiliate_id]);
					if(!$parent_person) {
						throw new NotAcceptableHttpException("Invalid promo code");
					}
				}
				break;
			case Person::DEVISER:
			case Person::INFLUENCER:
				$invitation_id = Yii::$app->request->post("uuid");
				/** @var Invitation $invitation */
				$invitation = Invitation::findOneSerialized($invitation_id);

				if (!$invitation) {
					throw new NotFoundHttpException("Invitation not found");
				}

				if (!$invitation->canUse()) {
					throw new BadRequestHttpException("Invalid invitation");
				}

				if ($invitation->email != Yii::$app->request->post('email')) {
					throw new BadRequestHttpException("The invitation is for another email account");
				}
				$account_state = Person::ACCOUNT_STATE_DRAFT;
				break;
			default:
				throw new BadRequestHttpException("Invalid person type");
		}

		$email = Yii::$app->request->post('email');
		$personExists = Person::findByEmail($email);
		if ($personExists) {
			throw new ConflictHttpException("Email ".$email." already in use");
		}

		$person = new Person();
		$person->type = $type;
		$person->account_state = $account_state;
		$person->affiliate_id = "AF" . $person->short_id;
		if(isset($parent_person)) {
			$person->parent_affiliate_id = $parent_affiliate_id;
			$person->setAttribute('follow', array($parent_person->short_id));
		} else {
			$person->parent_affiliate_id = "";
		}

		$person->setScenario($this->getScenarioFromRequest($person));
		$person->load(Yii::$app->request->post(), '');

		$person->credentials = ["email" => $email];
		$person->setPassword(Yii::$app->request->post("password"));

		// Load personal info directly to subdocument
		$person->personalInfoMapping->load(Yii::$app->request->post(), '');
		// Refresh properties from the embed
		$person->refreshFromEmbedded();

		if ($person->validate()) {
			$person->save(false);

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
     * @return array
     * @throws \Exception
     * Function returns number of person based on request params and type of response ("devisers" or "influencers")
     */
	public function actionCount()
    {
        // show only fields needed in this scenario
        Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_COUNT);

        $type = Yii::$app->request->get("type");

        $query = Person::findSerialized([
            "id" => Yii::$app->request->get("id"),
            "name" => Yii::$app->request->get("name"), // search only in name attribute
            "text" => Yii::$app->request->get("q"), // search in name, description, and more
            "type" => $type,
            "categories" => Yii::$app->request->get("categories"),
            "countries" => Yii::$app->request->get("countries"),
            "account_state" => Person::ACCOUNT_STATE_ACTIVE,
        ]);
        $count = Person::$countItemsFound;
        return [
            "type"  => ($type == 2) ? 'devisers' : (($type == 3) ? 'influencers' : ''),
            "count" => $count,
        ];
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
