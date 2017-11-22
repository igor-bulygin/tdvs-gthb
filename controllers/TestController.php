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
		$short_id = '976966c0';
		$order = \app\models\Order::findOneSerialized($short_id);
		if ($order) {
			$order->composeEmailOrderPaid(false);
		}
	}

	public function actionMandrill()
	{
//		$scheduleds = EmailsHelper::listScheduled();
//
//		foreach ($scheduleds as $scheduled) {
//			EmailsHelper::cancelScheduled($scheduled['_id']);
//		}
//
		$scheduleds = EmailsHelper::listScheduled();
		var_dump($scheduleds);
		die;

		$short_id = '976966c0';
		$order = \app\models\Order::findOneSerialized($short_id);
		if ($order) {
			$order->scheduleEmailsNewOrder();
		} else {
			throw new Exception('Order ' . $short_id . ' not found');
		}
	}
}