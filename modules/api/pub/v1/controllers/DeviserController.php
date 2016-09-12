<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Person;
use app\models\PostmanEmail;
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
	public function actionInvitationRequestsPost()
	{
		$form = new BecomeDeviserForm();
		if ($form->load(Yii::$app->request->post(), '') && $form->validate()) {
			// handle success
			$email = new PostmanEmail();
			$email->code_email_content_type = PostmanEmail::EMAIL_CONTENT_TYPE_DEVISER_REQUEST_INVITATION;
			$email->to_email = $form->email;
			$email->subject = 'Deviser invitation request';

			$email->body_html = Yii::$app->view->render(
				'request-invitation',
				[
					"form" => $form,
				],
				$this
			);
			$email->save();

			$email->send();

			Yii::$app->response->setStatusCode(201); // Success (without body)
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

