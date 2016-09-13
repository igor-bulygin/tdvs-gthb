<?php
namespace app\controllers;

use app\models\PostmanEmail;
use app\models\PostmanEmailAction;
use Yii;
use app\models\Tag;
use app\models\Lang;
use yii\helpers\Json;
use app\helpers\Utils;
use app\models\Person;
use app\models\Country;
use app\models\Product;
use app\models\Category;
use app\models\SizeChart;
use app\models\MetricType;
use yii\filters\VerbFilter;
use app\helpers\CController;
use app\helpers\CActiveRecord;
use yii\filters\AccessControl;
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
