<?php
namespace app\controllers;

use app\helpers\CAccessRule;
use app\helpers\CController;
use app\models\Person;
use app\models\PersonStripeInfo;
use yii\filters\AccessControl;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;

class SettingsController extends CController
{
	public $defaultAction = "index";

	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'ruleConfig' => [
								'class' => CAccessRule::className(),
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

	public function actionIndex($slug, $person_id) {
		return $this->actionBilling($slug, $person_id);
	}

	public function actionBilling($slug, $person_id)
	{
		// get the category object
		$person = Person::findOneSerialized($person_id);

		if (!$person->isDeviser() || !$person->isDeviserEditable()) {
			throw new NotFoundHttpException();
		}

		$this->layout = '/desktop/public-2.php';

		return $this->render("billing", [
			'person' => $person,
		]);
	}

	public function actionConnectStripe($slug, $person_id) {

		// get the category object
		$person = Person::findOneSerialized($person_id);

		if (!$person->isDeviser() || !$person->isDeviserEditable()) {
			throw new NotFoundHttpException();
		}

		\Yii::$app->session->set('person_id_stripe_connection', $person->short_id);

		$this->redirect('https://connect.stripe.com/oauth/authorize?response_type=code&client_id='.\Yii::$app->params['stripe_client_id'].'&scope=read_write');
	}

	public function actionDisconnectStripe($slug, $person_id) {

		// get the category object
		$person = Person::findOneSerialized($person_id);

		if (!$person->isDeviser() || !$person->isDeviserEditable()) {
			throw new NotFoundHttpException();
		}

		$apiKey = \Yii::$app->params['stripe_secret_key'];
		$clientId = \Yii::$app->params['stripe_client_id'];
		$stripeUserId = $person->settingsMapping->stripeInfoMapping->stripe_user_id;

		$curl = curl_init();
		curl_setopt_array($curl, array(
			CURLOPT_RETURNTRANSFER => 1,
			CURLOPT_URL => 'https://connect.stripe.com/oauth/deauthorize',
			CURLOPT_POST => 1,
			CURLOPT_USERPWD => $apiKey . ':',
			CURLOPT_POSTFIELDS => http_build_query(array(
				'client_id' => $clientId,
				'stripe_user_id' => $stripeUserId,
			))
		));
		$resp = curl_exec($curl);
		curl_close($curl);

		$person->settingsMapping->stripeInfoMapping = new PersonStripeInfo();
		$person->save();

		$this->redirect(Url::to(['settings/billing', 'slug' => $person->slug, 'person_id' => $person->short_id]));

	}
}
