<?php

namespace app\controllers;

use app\helpers\CController;
use app\helpers\InstagramHelper;
use app\models\Person;
use Yii;
use yii\web\NotFoundHttpException;


class InstagramController extends CController
{

	public function actionConnectBack()
	{
		Yii::info('Instagram connect back', __METHOD__);

		$person_id = Yii::$app->session->get('person_id_instagram_connection');
		if (empty($person_id)) {
			throw new \Exception("Missing required person identifier");
		}
		Yii::$app->session->remove('person_id_instagram_connection');

		// This action must be done in Owner scenario to get the person object equal to the database object
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($person_id);
		if (!$person->isPersonEditable()) {
			throw new NotFoundHttpException();
		}

		$code = Yii::$app->request->get('code');

		if ($code) {

			// Validate token
			$resp = InstagramHelper::validateAuthorizationToken($code);

			if (!$resp) {
				Yii::info('Instagram connect back with errors. Validation result: '.$resp, __METHOD__);
			} else {
				// Save current connect info
				$person->settingsMapping->instagram_info = $resp;
				$person->save();
			}

			$this->redirect($person->getSocialLink());

		} else if (isset($_GET['error'])) {

			Yii::info('Instagram connect back with errors: '.$_GET['error_description'], __METHOD__);

			$this->redirect($person->getSocialLink());

		} else {

			$this->redirect($person->getSocialLink());

		}
	}
}
