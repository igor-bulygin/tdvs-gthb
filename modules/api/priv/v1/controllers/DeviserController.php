<?php

namespace app\modules\api\priv\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Person;
use app\modules\api\priv\v1\forms\UploadForm;
use Yii;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;
use yii\web\UploadedFile;
use yii\web\User;

class DeviserController extends Controller
{

	public function init()
	{
		parent::init();

		// TODO: retrieve current identity from one of the available authentication methods in Yii
		Yii::$app->user->login(Person::findOne(["short_id" => "13cc33k"]));
	}

	public function actionView()
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		/** @var Person $deviser */
		$deviser = Yii::$app->user->getIdentity();

		return $deviser;
	}

	public function actionUpdate()
	{
		/** @var Person $deviser */
		$deviser = Yii::$app->user->getIdentity();

//        $data = Yii::$app->request->post();
//        print_r($data);

		$deviser->setScenario($this->getScenarioFromRequest());
		if ($deviser->load(Yii::$app->request->post(), '') && $deviser->save()) {
			// handle success

			// TODO: return the deviser data, only for test. remove when finish.
//            Yii::$app->response->setStatusCode(204); // Success, without body
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
	 * @throws BadRequestHttpException
	 * @return string
	 */
	private function getScenarioFromRequest()
	{
		// get scenario to use in validations, from request
		$scenario = Yii::$app->request->post('scenario', Person::SCENARIO_DEVISER_PROFILE_UPDATE);

		// check that is a valid scenario for this controller
		if (!in_array($scenario, [
			Person::SCENARIO_DEVISER_PROFILE_UPDATE,
			Person::SCENARIO_DEVISER_PRESS_UPDATE,
			Person::SCENARIO_DEVISER_VIDEOS_UPDATE,
			Person::SCENARIO_DEVISER_FAQ_UPDATE,
		])
		) {
			throw new BadRequestHttpException('Invalid scenario');
		}

		return $scenario;
	}
}

