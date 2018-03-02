<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\helpers\EmailsHelper;
use app\models\Chat;
use app\models\Order;
use app\models\OrderPack;
use app\models\Person;
use yii\console\Controller;

class RemindersController extends Controller
{
    public function actionIndex()
    {
    	$this->sendSmsDevisersReminders();
    	$this->sendUnreadMessagesReminders();
    }

	protected function sendSmsDevisersReminders()
	{
		$hoursFrom = \DateInterval::createFromDateString('96 hours');
		$hoursTo = \DateInterval::createFromDateString('72 hours');

		$dateFrom = (new \DateTime())->sub($hoursFrom)->format('Y-m-d H:i:s');
		$dateTo = (new \DateTime())->sub($hoursTo)->format('Y-m-d H:i:s');

		$date_from_formatted = strtotime($dateFrom);
		$date_to_formatted = strtotime($dateTo);

		$orders = Order::findSerialized(
			[
				'order_date_from' => new \MongoDate($date_from_formatted),
				'order_date_to' => new \MongoDate($date_to_formatted),
				'order_state' => Order::ORDER_STATE_PAID,
				'pack_state' => OrderPack::PACK_STATE_PAID,
			]
		);
		foreach ($orders as $order) {
			$packs = $order->getPacks();
			foreach ($packs as $pack) {
				if ($pack->pack_state == OrderPack::PACK_STATE_PAID) {

					if (!$pack->getDeviser()->personalInfoMapping->getPhoneNumber()) {
						echo "Deviser ".$pack->getDeviser()->getName()." (pack ".$pack->short_id.", order ".$order->short_id.") does not have a phone number\n";
						continue; // Deviser does not have a phone number
					}

					if ($pack->hasSentSmsNewOrderReminder72()) {
						echo "Message (pack ".$pack->short_id.", order ".$order->short_id.") already sent\n";
						continue; // Already sent
					}

					echo "sending message for order ".$order->short_id." pack ".$pack->short_id."\n";

					$pack->sendSmsNewOrderReminder72();
					// Set pack in the order and save
					$order->setPack($pack->short_id, $pack);
					$order->save();
				}
			}
		}
	}

	protected function sendUnreadMessagesReminders()
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
