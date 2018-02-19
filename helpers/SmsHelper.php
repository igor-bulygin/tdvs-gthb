<?php
namespace app\helpers;

use app\models\Order;
use Twilio\Rest\Client;
use Yii;
use yii\base\Exception;

class SmsHelper
{
	public static function deviserNewOrder(Order $order, $packId)
	{
		$subject = 'YOU HAVE A NEW SALE';

		$send_at = (new \DateTime())->format('Y-m-d H:i:s');

		$params = static::mergeCustomWithCommonVars(
			static::getOrderPackCommonVars($order, $packId),
			[
				'header' => 'You have a new sale',
				'hello' => 'Hello {{deviser_name}}',
				'text' => '{{client_name}} bought the following products:',
				'text_2' => 'Please log into your Todevise profile and let us know that you are aware of the sale (even if you are not ready to send it yet, we need to know that you saw the notification).',
				'button_text' => 'GO TO MY PROFILE',
				'button_link' => '{{deviser_link_profile}}',
			]
		);

		return static::sendTemplate($subject, 'default', $params, 'deviser', $send_at);
	}

	public static function deviserNewOrderReminder72(Order $order, $packId)
	{
		$subject = 'REMINDER: YOU HAVE A NEW SALE';

		$send_at = (new \DateTime())->add(\DateInterval::createFromDateString('24 hours'))->format('Y-m-d H:i:s');

		$params = static::mergeCustomWithCommonVars(
			static::getOrderPackCommonVars($order, $packId),
			[
				'header' => 'REMINDER: YOU HAVE A NEW SALE',
				'hello' => 'Hello {{deviser_name}}',
				'text' => '{{client_name}} bought the following products:',
				'text_2' => 'Please let us know that you saw this sale & fill in the shipping information inside your profile when you shipped the package. To do so, go to SETTINGS - MY ORDERS.',
				'button_text' => 'GO TO MY PROFILE',
				'button_link' => '{{deviser_link_profile}}',
			]
		);

		return static::sendTemplate($subject, 'default', $params, 'deviser', $send_at);
	}

	protected static function sendSms($to, $body)
	{
		try {


			$from = '+15005550006';

			// Your Account SID and Auth Token from twilio.com/console
			$sid = \Yii::$app->params['twilio_account_id'];
			$token = \Yii::$app->params['twilio_auth_token'];
			$client = new Client($sid, $token);

			// Use the client to do fun stuff like send text messages!
			$result = $client->messages->create(
				$to,
				array(
					'from' => $from,
					'body' => $body,
				)
			);

			var_dump($result);

			return $result;

		} catch (Exception $e) {
			echo 'A twilio error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			throw $e;
		}
	}


	protected static function getOrderCommonVars(Order $order)
	{
		$packs = $order->getPacks();

		$client = $order->getPerson();
		$clientEmail = $client->getEmail();
		$clientName = $client->getName();
		$clientProfileLink = Yii::$app->getUrlManager()->getHostInfo() . $client->getSettingsLink('open-orders');

		$productsVars = [];
		foreach ($packs as $pack) {
			$deviser = $pack->getDeviser();
			$products = $pack->getProducts();
			foreach ($products as $packProduct) {
				$product = $packProduct->getProduct();
				$productsVars[] = [
					'name' => $deviser->getName().' - '.$product->getName(),
					'quantity' => $packProduct->quantity,
					'price' => $packProduct->price * $packProduct->quantity . '€',
					'link' => $product->getViewLink(),
					'image' => Yii::$app->getUrlManager()->getHostInfo() . '/' . $product->getMainImage(),
				];
			}
		}

		$merge_vars = [
			'client_name' => $clientName,
			'client_email' => $clientEmail,
			'client_link_profile' => $clientProfileLink,
			'products' => $productsVars,
			'order_number' => $order->short_id,
		];

		return $merge_vars;
	}

	protected static function getOrderPackCommonVars(Order $order, $packId)
	{
		$pack = $order->getPack($packId);
		if (empty($pack)) {
			throw new Exception(sprintf('Pack with id %s does not exists in the order', $packId));
		}
		$deviser = $pack->getDeviser();
		$deviserEmail = $deviser->getEmail();
		$deviserName = $deviser->getName();
		$deviserProfileLink = Yii::$app->getUrlManager()->getHostInfo() . $deviser->getSettingsLink('open-orders');

		$client = $order->getPerson();
		$clientEmail = $client->getEmail();
		$clientName = $client->getName();
		$clientProfileLink = Yii::$app->getUrlManager()->getHostInfo() . $client->getSettingsLink('open-orders');

		$productsVars = [];
		$products = $pack->getProducts();
		foreach ($products as $packProduct) {
			$product = $packProduct->getProduct();
			$productsVars[] = [
				'name' => $product->getName(),
				'quantity' => $packProduct->quantity,
				'price' => $packProduct->price * $packProduct->quantity . '€',
				'link' => $product->getViewLink(),
				'image' => Yii::$app->getUrlManager()->getHostInfo() . '/' . $product->getMainImage(),
			];
		}

		$merge_vars = [
			'client_name' => $clientName,
			'client_email' => $clientEmail,
			'client_link_profile' => $clientProfileLink,
			'deviser_name' => $deviserName,
			'deviser_email' => $deviserEmail,
			'deviser_link_profile' => $deviserProfileLink,
			'products' => $productsVars,
			'order_number' => $order->short_id.'/'.$pack->short_id,
			'company' => isset($pack->shipping_info['company']) ? $pack->shipping_info['company'] : '-',
			'tracking_number' => isset($pack->shipping_info['tracking_number']) ? $pack->shipping_info['tracking_number'] : '-',
			'tracking_link' => isset($pack->shipping_info['tracking_link']) ? '<a href="'.$pack->shipping_info['tracking_link'].'">'.$pack->shipping_info['tracking_link'].'</a>' : '-',
		];

		return $merge_vars;
	}

	protected static function mergeCustomWithCommonVars($commonVars, $customVars)
	{
		foreach ($commonVars as $commonName => $commonContent) {
			foreach ($customVars as $name => $content) {
				if (is_string($commonContent)) {
					$customVars[$name] = str_replace('{{' . $commonName . '}}', $commonContent, $content);
				}
			}
		}

		$commonVars = array_merge($commonVars, $customVars);

		return $commonVars;
	}


	public static function test()
	{
//		die;
		static::sendSms('+34657454038', 'probando');
	}
}