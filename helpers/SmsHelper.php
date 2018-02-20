<?php
namespace app\helpers;

use app\models\Order;
use Twilio\Exceptions\RestException;
use Twilio\Rest\Client;
use Yii;
use yii\base\Exception;

class SmsHelper
{
	public static function deviserNewOrder(Order $order, $packId)
	{
		$pack = $order->getPack($packId);

		if (!$pack) {
			throw new Exception("Pack id not found");
		}

		$deviser = $pack->getDeviser();
		$to = $deviser->personalInfoMapping->getPhoneNumber();
		if (!$to) {
			throw new Exception("Deviser " . $deviser->getName() . " has no phone number");
		}

		$body = 'New sale {{order_number}}. Please go to SETTINGS-MY ORDERS and let us know that you are aware {{deviser_link_profile}}';

		$params = static::mergeCustomWithCommonVars(static::getOrderPackCommonVars($order, $packId), []);

		$body = str_replace(array_keys($params), $params, $body);

		static::sendSms($to, $body);

		return [
			'to' => $to,
			'body' => $body,
		];
	}

	public static function deviserNewOrderReminder72(Order $order, $packId)
	{
		$pack = $order->getPack($packId);

		if (!$pack) {
			throw new Exception("Pack id not found");
		}

		$deviser = $pack->getDeviser();
		$to = $deviser->personalInfoMapping->getPhoneNumber();
		if (!$to) {
			throw new Exception("Deviser " . $deviser->getName() . " has no phone number");
		}

		$body = 'REMINDER: YOU HAVE A NEW SALE. Please let us know that you saw the sale {{order_number}} {{deviser_link_profile}}';

		$params = static::mergeCustomWithCommonVars(static::getOrderPackCommonVars($order, $packId), []);

		$body = str_replace(array_keys($params), $params, $body);

		static::sendSms($to, $body);

		return [
			'to' => $to,
			'body' => $body,
		];
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

//			var_dump($result);

			return $result;

		} catch (RestException $e) {
			switch ($e->getCode()) {
				case 21212:
					// This phone number is invalid
					break;

				case 21612:
					//Twilio cannot route to this number.
					break;

				case 21408:
					//Your account doesn't have the international permissions necessary to SMS this number.
					break;

				case 21610:
					//This number is blacklisted for your account.
					break;

				case 21614:
					//This number is incapable of receiving SMS messages.
					break;

				case 21606:
					//This phone number is not owned by your account or is not SMS-capable.
					break;

				case 21611:
					//This number has an SMS message queue that is full.
					break;
			}


			Yii::info('Twilio error: '.$e->getMessage(), __METHOD__);

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
		$totalProducts = 0;
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
			$totalProducts += $packProduct->quantity;
		}

		$merge_vars = [
			'{{client_name}}' => $clientName,
			'{{client_email}}' => $clientEmail,
			'{{client_link_profile}}' => $clientProfileLink,
			'{{deviser_name}}' => $deviserName,
			'{{deviser_email}}' => $deviserEmail,
			'{{deviser_link_profile}}' => $deviserProfileLink,
//			'{{products}}' => $productsVars,
			'{{number_products}}' => $totalProducts,
			'{{order_number}}' => $order->short_id.'/'.$pack->short_id,
			'{{company}}' => isset($pack->shipping_info['company']) ? $pack->shipping_info['company'] : '-',
			'{{tracking_number}}' => isset($pack->shipping_info['tracking_number']) ? $pack->shipping_info['tracking_number'] : '-',
			'{{tracking_link}}' => isset($pack->shipping_info['tracking_link']) ? '<a href="'.$pack->shipping_info['tracking_link'].'">'.$pack->shipping_info['tracking_link'].'</a>' : '-',
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
		static::sendSms('+15005550009', 'probando');
	}
}