<?php

namespace app\controllers;

use app\helpers\CController;
use app\helpers\EmailsHelper;
use app\models\Chat;
use app\models\Person;
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

	public function actionOrderEmails()
	{
		die;

		$short_id = '976966c0';
		$order = \app\models\Order::findOneSerialized($short_id);
		if ($order) {
			$order->scheduleEmailsNewOrder();
			$packs = $order->getPacks();
			foreach ($packs as $pack) {
				$pack->scheduleEmailsNoShipped();
			}
		}
	}

	public function actionMandrill()
	{
		die;

		$scheduleds = EmailsHelper::listScheduled();
		foreach ($scheduleds as $scheduled) {
			EmailsHelper::cancelScheduled($scheduled['_id']);
		}

		$short_id = '976966c0';
		$order = \app\models\Order::findOneSerialized($short_id);
		if ($order) {
			$order->scheduleEmailsNewOrder();
		} else {
			throw new Exception('Order ' . $short_id . ' not found');
		}

		$scheduleds = EmailsHelper::listScheduled();
		var_dump($scheduleds);
		die;
	}

	public function actionSms()
	{
		$short_id = '976966c0';
		$order = \app\models\Order::findOneSerialized($short_id);
		$order->sendSmsNewOrder();
		$order->sendSmsNewOrderReminder72();

		var_dump($order->getAttributes());

	}

	public function actionUnreadChats()
	{

		// find unread messages for 24horas or more
		$chats = Chat::findSerialized();
		foreach ($chats as $chat) {
			foreach ($chat->unread_by as $person_id) {
				$receiver = Person::findOneSerialized($person_id);
				$message = $chat->getLastMessage($receiver);
				if ($message) {
					// TODO: check if sender and receiver are buyer and seller

					EmailsHelper::unreadChat($receiver, $message);
				}
			}

		}
	}

	public function actionEnv()
	{
		echo '<pre> $_ENV: '.print_r($_ENV, true).'</pre>';
		echo '<pre> getenv(null): '.print_r(getenv(null), true).'</pre>';
		echo '<pre> YII_ENV: '.print_r(YII_ENV, true).'</pre>';
		echo '<pre> YII_ENV_DEV: '.print_r(YII_ENV_DEV, true).'</pre>';
		echo '<pre> YII_ENV_TEST: '.print_r(YII_ENV_TEST, true).'</pre>';
		echo '<pre> YII_ENV_PROD: '.print_r(YII_ENV_PROD, true).'</pre>';
		echo '<pre> ENVIRONMENT: '.print_r(ENVIRONMENT, true).'</pre>';
		echo '<pre> THUMBOR_SERVER: '.print_r(getenv('THUMBOR_SERVER'), true).'</pre>';
		echo '<pre> THUMBOR_SECURITY_KEY: '.print_r(getenv('THUMBOR_SECURITY_KEY'), true).'</pre>';
	}
}