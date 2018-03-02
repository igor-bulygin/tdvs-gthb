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
}