<?php
/**
 * @link http://www.yiiframework.com/
 * @copyright Copyright (c) 2008 Yii Software LLC
 * @license http://www.yiiframework.com/license/
 */

namespace app\commands;

use app\models\Order;
use app\models\OrderPack;
use yii\console\Controller;

class SmsTaskController extends Controller
{
    public function actionDeviserReminders()
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
					$send = true;
    				if (is_array($pack->sms_sent) && isset($pack->sms_sent['deviser_new_order_reminder_72'])) {
						foreach ($pack->sms_sent['deviser_new_order_reminder_72'] as $item) {
							if (isset($item['result']) && $item['result'] == 'sent') {
								$send = false;
								break;
							}
						}
					}
					if (!$send) {
    					continue;
					}
    				$pack->sendSmsNewOrderReminder72();
					// Set pack in the order and save
					$order->setPack($pack->short_id, $pack);
					$order->save();
				}
			}
		}
    }
}
