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

class AppPrivateController extends Controller
{

	public function init()
	{
		parent::init();

		// TODO: retrieve current identity from one of the available authentication methods in Yii
//		$deviser_id = Yii::$app->request->isGet ? Yii::$app->request->get("deviser_id") : Yii::$app->request->post("deviser_id");
//		if (empty($deviser_id)) {
//			throw new BadRequestHttpException('Deviser not specified');
//		}
//		Yii::$app->user->login(Person::findOne(["short_id" => $deviser_id]));
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

