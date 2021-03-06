<?php

namespace app\modules\api\admin\v1\controllers;

use app\models\Faq;
use app\models\Invitation;
use app\models\Person;
use MongoDate;
use Yii;
use yii\rest\Controller;
use yii\web\ConflictHttpException;
use yii\web\ForbiddenHttpException;

class InvitationController extends Controller
{

	public function actionIndex()
	{
		// set the scenario to serialize objects
		Invitation::setSerializeScenario(Faq::SERIALIZE_SCENARIO_ADMIN);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 99999);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));


		return Invitation::findSerialized([
			"limit" => $limit,
			"offset" => $offset,
		]);
	}

	public function actionCreate()
	{
		$email = Yii::$app->request->post('email');
		$personExists = Person::findByEmail($email);
		if ($personExists) {
			throw new ConflictHttpException("Email ".$email." already in use");
		}

		$invitation = new Invitation();
		if ($invitation->load(Yii::$app->request->post(), '') && $invitation->validate()) {
			// save the invitation
			$invitation->save(false);

			$no_invitation = Yii::$app->request->post('no_email', false);

			// send the email to invited person
			$email = $invitation->composeEmail();
			$taskId = $email->findCurrentPendingTaskId();
			$url = null;
			if ($no_invitation || $email->send($taskId)) {
				$email->save();

				if (!$no_invitation) {
					$invitation->date_sent = new MongoDate();
					$invitation->save();
				}


				if ($no_invitation && !empty($email->actions)) {
					$url = $invitation->getActionUrl($email->actions[0]['uuid']);
				}
			}

			Yii::$app->response->setStatusCode(201); // Created
			return [
				"action" => "created",
				"url" => $url,
				"uuid" => $invitation->uuid,
			];
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $invitation->errors];
		}
	}

	/**
	 * Checks the privilege of the current user.
	 *
	 * This method should be overridden to check whether the current user has the privilege
	 * to run the specified action against the specified data model.
	 * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
	 *
	 * @param string $action the ID of the action to be executed
	 * @param \yii\base\Model $model the model to be accessed. If null, it means no specific model is being accessed.
	 * @param array $params additional parameters
	 * @throws ForbiddenHttpException if the user does not have access
	 */

	public function checkAccess($action, $model = null, $params = [])
	{
		// check if the user can access $action and $model
		// throw ForbiddenHttpException if access should be denied
	}
}

