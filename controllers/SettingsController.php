<?php
namespace app\controllers;

use app\helpers\CAccessRule;
use app\helpers\CController;
use app\models\Person;
use Yii;
use yii\filters\AccessControl;

class SettingsController extends CController
{
	public $defaultAction = "index";

	public function behaviors()
	{
		return [
				'access' => [
						'class' => AccessControl::className(),
						'ruleConfig' => [
								'class' => CAccessRule::className(),
						],
						'rules' => [
								[
										'allow' => true,
										'roles' => ['@'],
								],
						],
				],
		];
	}

	public function actionIndex($slug, $person_id) {
		return $this->actionBilling($slug, $person_id);
	}

	public function actionBilling($slug, $person_id)
	{
		// get the category object
		$person = Person::findOneSerialized($person_id);

		$this->layout = '/desktop/public-2.php';
		return $this->render("billing", [
				'person' => $person,
		]);
	}
}
