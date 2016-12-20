<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\Utils;
use app\models\Invitation;
use app\models\Person;
use app\models\PostmanEmail;
use app\models\PostmanEmailTask;
use app\modules\api\pub\v1\forms\BecomeDeviserForm;
use MongoDate;
use Yii;
use yii\web\BadRequestHttpException;

class DeviserController extends AppPublicController
{

	/**
	 * Create a new Deviser account
	 *
	 * @return Person|array|void
	 * @throws BadRequestHttpException
	 */
	public function actionCreate()
	{

		$deviser = new Person();

		$invitation_id = Yii::$app->request->post("invitation_id");
		/** @var Invitation $invitation */
		$invitation = Invitation::findOneSerialized($invitation_id);

		if (!$invitation) {
			throw new BadRequestHttpException(Yii::t("app/api", "Invitation not found"));
		}

		if (!$invitation->canUse()) {
			throw new BadRequestHttpException(Yii::t("app/api", "Invalid invitation"));
		}

		if ($invitation->email != Yii::$app->request->post('email')) {
			throw new BadRequestHttpException(Yii::t("app/api", "The invitation is for another email account"));
		}

		$deviser->setScenario(Person::SCENARIO_DEVISER_CREATE_DRAFT);
		$deviser->load(Yii::$app->request->post(), '');
		$deviser->personalInfo->load(Yii::$app->request->post(), '');

		$deviser->credentials = ["email" => $invitation->email];
		$deviser->setPassword(Yii::$app->request->post("password"));
		$deviser->type = [Person::DEVISER];

		if ($deviser->validate()) {
			$deviser->save();

			// relate invitation and new deviser
			$invitation->person_id = $deviser->_id;

			// indicate that invitation has been used
			$invitation->setAsUsed()->save();

			// return information needed to client side
			Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_PUBLIC);
			return $deviser;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $deviser->errors];
		}
	}

		/**
	 * Process the request of new Devisers to join the platform
	 *
	 * @return array|void
	 */
	public function actionInvitationRequestsPost()
	{
		$form = new BecomeDeviserForm();
		if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
			// handle success
			$email = new PostmanEmail();
			$email->code_email_content_type = PostmanEmail::EMAIL_CONTENT_TYPE_DEVISER_REQUEST_INVITATION;
			$email->to_email = $form->email;
			$email->subject = 'Deviser invitation request';

			// add task only one send task (to allow retries)
			$task = new PostmanEmailTask();
			$task->date_send_scheduled = new MongoDate();
			$email->addTask($task);

			$email->body_html = Yii::$app->view->render(
				'request-invitation',
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
	 * Returns the directory containing view files for this controller.
	 * The default implementation returns the directory named as controller [[id]] under the [[module]]'s
	 * [[viewPath]] directory.
	 * @return string the directory containing the view files for this controller.
	 */
	public function getViewPath()
	{
		return Utils::join_paths('@app', 'mail', 'deviser');
	}
}

