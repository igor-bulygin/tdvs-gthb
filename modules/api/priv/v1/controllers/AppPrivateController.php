<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Person;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;

class AppPrivateController extends Controller
{
	public function init()
	{
		parent::init();

		\Yii::$app->user->enableSession = false; // restfull must be stateless => no session
		\Yii::$app->user->loginUrl = null; 		 // force 403 response
	}

	public function behaviors()
	{

		$behaviors = parent::behaviors();

		$behaviors['authenticator'] = [
				'class' => HttpBearerAuth::className(),
		];
		$behaviors['access'] = [
				'class' => AccessControl::className(),
				'rules' => [
						[
								'allow' => true,
								'roles' => ['@'] //? guest, @ authenticated
						]
				]
		];

		return $behaviors;
	}

	/**
	 * @return Person
	 * @throws BadRequestHttpException
	 */
	protected function getPerson()
	{
		// TODO: retrieve current identity from one of the available authentication methods in Yii
		$person_id = Yii::$app->request->isGet ? Yii::$app->request->get("deviser_id") : Yii::$app->request->post("deviser_id");
		if (empty($person_id)) {
			throw new BadRequestHttpException('Person id not specified');
		}
		/** @var Person $person */
		$person = Person::findOne(["short_id" => $person_id]);
		if (empty($person)) {
			throw new BadRequestHttpException('Person not found');
		}

		return $person;

	}

}

