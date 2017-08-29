<?php
namespace app\controllers;

use app\helpers\CController;
use app\models\PostmanEmail;
use app\models\PostmanEmailAction;
use yii\web\NotFoundHttpException;

class PostmanController extends CController {

//	public function actionIndex()
//	{
//		$emails = PostmanEmail::findSerialized(
//			[
//				"limit" => 50,
//				"order_by" => ç,
//			]
//		);
//
//		$this->layout = '/desktop/public-2.php';
//		return $this->render("email-list", [
//			'emails' => $emails,
//		]);
//	}

	public function actionMockupDeviserRequestInvitationView()
	{
		$this->layout = '/desktop/empty-layout.php';
		return $this->render("@app/mail/deviser/request-invitation");
	}

	public function actionMockupDeviserInvitationView()
	{
		$actionAccept = new PostmanEmailAction();
		$this->layout = '/desktop/empty-layout.php';
		return $this->render("@app/mail/deviser/invitation", [
			"actionAccept" => $actionAccept,
		]);
	}

	public function actionEmailView($uuid)
	{
		$email = PostmanEmail::findOneSerialized($uuid);
		if (!$email) {
			throw new NotFoundHttpException('Email not found');
		}

		$this->layout = '/desktop/empty-layout.php';
		return $this->render("email-view", [
			'email' => $email,
		]);
	}

}
