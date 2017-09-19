<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Invitation;
use app\models\Person;
use app\models\PostmanEmail;
use app\models\PostmanEmailTask;
use app\modules\api\pub\v1\forms\BecomeDeviserForm;
use app\modules\api\pub\v1\forms\BecomeInfluencerForm;
use Yii;
use yii\web\ConflictHttpException;
use yii\web\NotFoundHttpException;

class InvitationController extends AppPublicController {

	public function actionView($uuid)
	{
		// show only fields needed in this scenario
		Invitation::setSerializeScenario(Invitation::SERIALIZE_SCENARIO_PUBLIC);

		/** @var Invitation $invitation */
		$invitation = Invitation::findOneSerialized($uuid);
		if (!$invitation) {
			throw new NotFoundHttpException("Invitation not found");
		}

		return $invitation;
	}

	/**
	 * Process the request of new Devisers to join the platform
	 *
	 */
	public function actionRequestBecomeDeviser()
	{
		$email = Yii::$app->request->post('email');
		$personExists = Person::findByEmail($email);
		if ($personExists) {
			throw new ConflictHttpException("Email ".$email." already in use");
		}

		$form = new BecomeDeviserForm();
		if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
			// handle success
			$email = new PostmanEmail();
			$email->code_email_content_type = PostmanEmail::EMAIL_CONTENT_TYPE_DEVISER_REQUEST_INVITATION;
			$email->to_email = 'info@todevise.com';
			$email->subject = 'New deviser invitation request';

			// add task only one send task (to allow retries)
			$task = new PostmanEmailTask();
			$task->date_send_scheduled = new \MongoDate();
			$email->addTask($task);

			$email->body_html = Yii::$app->view->render(
				'@app/mail/deviser/request-invitation',
				[
					"form" => $form,
				],
				$this
			);
			$email->save();

			if ($email->send($task->id)) {
				$email->save();
			}

			Yii::$app->response->setStatusCode(201); // Created
//			return ["action" => "done"];
			return null;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $form->errors];
		}
	}

	/**
	 * Process the request of new Influencers to join the platform
	 *
	 */
	public function actionRequestBecomeInfluencer()
	{
		$email = Yii::$app->request->post('email');
		$personExists = Person::findByEmail($email);
		if ($personExists) {
			throw new ConflictHttpException("Email ".$email." already in use");
		}

		$form = new BecomeInfluencerForm();
		if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
			// handle success
			$email = new PostmanEmail();
			$email->code_email_content_type = PostmanEmail::EMAIL_CONTENT_TYPE_INFLUENCER_REQUEST_INVITATION;
			$email->to_email = 'info@todevise.com';
			$email->subject = 'New influencer invitation request';

			// add task only one send task (to allow retries)
			$task = new PostmanEmailTask();
			$task->date_send_scheduled = new \MongoDate();
			$email->addTask($task);

			$email->body_html = Yii::$app->view->render(
				'@app/mail/influencer/request-invitation',
				[
					"form" => $form,
				],
				$this
			);
			$email->save();

			if ($email->send($task->id)) {
				$email->save();
			}

			Yii::$app->response->setStatusCode(201); // Created
//			return ["action" => "done"];
			return null;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $form->errors];
		}
	}

}

