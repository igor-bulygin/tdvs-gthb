<?php

namespace app\controllers;

use app\helpers\CController;
use app\helpers\EmailsHelper;
use yii\base\Exception;

class TestController extends CController
{
	public function beforeAction($action)
	{
		if (!YII_ENV_DEV) {
			throw new Exception('Trying to access test controller in a non dev environment');
		}

		return parent::beforeAction($action);
	}

	public function actionTestComposeEmailOrder()
	{
		$short_id = '3654fd24';
		$order = \app\models\Order::findOneSerialized($short_id);
		if ($order) {
			$order->composeEmailOrderPaid(false);
		}
	}

	public function actionMandrill()
	{
		$short_id = '5cf87f65';
		$order = \app\models\Order::findOneSerialized($short_id);
		if ($order) {
			EmailsHelper::setOrderPaidNotifications($order);
		}
	}
}