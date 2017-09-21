<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Newsletter;
use Yii;
use yii\web\ConflictHttpException;

class NewsletterController extends AppPublicController
{

	/**
	 * Create a new newsletter subscription
	 */
	public function actionCreate()
	{
		$email = Yii::$app->request->post('email');
		if (Newsletter::isSuscribedEmail($email)) {
			throw new ConflictHttpException("Email " . $email . " already in use"); // http 409
		}

		$newsletter = new Newsletter();
		$newsletter->load(Yii::$app->request->post(), '');

		if ($newsletter->validate()) {
			$newsletter = Newsletter::suscribeEmail($email, false);

			Yii::$app->response->setStatusCode(201); // Created

			Newsletter::setSerializeScenario(Newsletter::SERIALIZE_SCENARIO_PUBLIC);
			return $newsletter;

		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request

			return ["errors" => $newsletter->errors];
		}
	}
}

