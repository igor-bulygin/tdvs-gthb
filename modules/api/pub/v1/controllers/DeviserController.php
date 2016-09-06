<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Person;
use app\modules\api\pub\v1\forms\BecomeDeviserForm;
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

	/**
	 * Process the request of new Devisers to join the platform
	 *
	 * @return array|void
	 */
	public function actionRequestsPost()
	{
		$form = new BecomeDeviserForm();
		if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
			// handle success
			$mailer = Yii::$app->mailer;

			// TODO get emails and constants from .env file
			$mailer->compose('deviser/request-become', ['form' => $form])
				->setFrom('info@todevise.com')
				->setTo('info@todevise.com')
				->setSubject('Deviser request')
				->send();

			Yii::$app->response->setStatusCode(201); // Success (without body)
//			return ["action" => "done"];
			return null;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $form->errors];
		}
	}
}

