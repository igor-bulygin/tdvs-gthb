<?php
namespace app\helpers;

use app\models\Order;
use Yii;
use yii\base\Exception;

class EmailsHelper
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

	public static function deviserNewOrderReminder24(Order $order, $packId)
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

	public static function deviserNewOrderReminder48(Order $order, $packId)
	{
		$subject = 'REMINDER: ONE OF YOUR NEW SALES WASN’T ATTENDED';

		$send_at = (new \DateTime())->add(\DateInterval::createFromDateString('48 hours'))->format('Y-m-d H:i:s');

		$params = static::mergeCustomWithCommonVars(
			static::getOrderPackCommonVars($order, $packId),
			[
				'header' => 'REMINDER: ONE OF YOUR NEW SALES WASN’T ATTENDED',
				'hello' => 'Hello {{deviser_name}}',
				'text' => '{{client_name}} bought the following products:',
				'text_2' => 'You have an unattended sale. Please sign into your Todevise profile, let us know that you saw the sale and fill in the shipping information when you send the package.',
				'button_text' => 'GO TO MY PROFILE',
				'button_link' => '{{deviser_link_profile}}',
			]
		);

		return static::sendTemplate($subject, 'default', $params, 'deviser', $send_at);
	}

	public static function deviserOrderNoShippedReminder24(Order $order, $packId)
	{
		$subject = 'DID YOU SHIP THE PACKAGE?';

		$send_at = (new \DateTime())->add(\DateInterval::createFromDateString('24 hours'))->format('Y-m-d H:i:s');

		$params = static::mergeCustomWithCommonVars(
			static::getOrderPackCommonVars($order, $packId),
			[
				'header' => 'DID YOU SHIP THE PACKAGE?',
				'hello' => 'Hello {{deviser_name}}',
				'text' => '{{client_name}} bought the following products:',
				'text_2' => 'You let us know that you saw this sale. Were you able to ship the package? If so, please fill in the shipping information by going to SETTINGS - MY ORDERS, and clicking the PACKAGE WAS SHIPPED button.',
				'button_text' => 'GO TO MY PROFILE',
				'button_link' => '{{deviser_link_profile}}',
			]
		);

		return static::sendTemplate($subject, 'default', $params, 'deviser', $send_at);
	}

	public static function deviserOrderNoShippedReminder48(Order $order, $packId)
	{
		$subject = 'PLEASE FILL IN THE SHIPPING INFORMATION FOR ORDER #{{order_number}}';

		$send_at = (new \DateTime())->add(\DateInterval::createFromDateString('48 hours'))->format('Y-m-d H:i:s');

		$params = static::mergeCustomWithCommonVars(
			static::getOrderPackCommonVars($order, $packId),
			[
				'header' => 'PLEASE FILL IN THE SHIPPING INFORMATION FOR ORDER #{{order_number}}',
				'hello' => 'Hello {{deviser_name}}',
				'text' => '{{client_name}} bought the following products:',
				'text_2' => 'Please fill in the shipping information for the order #(número pedido). The customer will be informed that the package was shipped only if you fill in this information.',
				'button_text' => 'GO TO MY PROFILE',
				'button_link' => '{{deviser_link_profile}}',
			]
		);

		return static::sendTemplate($subject, 'default', $params, 'deviser', $send_at);
	}

	public static function deviserOrderNoShippedReminder72(Order $order, $packId)
	{
		$subject = 'IMPORTANT! SHIPPING INFORMATION MISSING FOR ORDER #{{order_number}}';

		$send_at = (new \DateTime())->add(\DateInterval::createFromDateString('72 hours'))->format('Y-m-d H:i:s');

		$params = static::mergeCustomWithCommonVars(
			static::getOrderPackCommonVars($order, $packId),
			[
				'header' => 'IMPORTANT! SHIPPING INFORMATION MISSING FOR ORDER #{{order_number}}',
				'hello' => 'Hello {{deviser_name}}',
				'text' => '{{client_name}} bought the following products:',
				'text_2' => 'At Todevise we always put our customers first, and we need to keep them updated on their orders. The shipping information for your order #(número pedido) is missing - please fill it in and click the PACKAGE WAS SHIPPED button as soon as possible.',
				'button_text' => 'GO TO MY PROFILE',
				'button_link' => '{{deviser_link_profile}}',
			]
		);

		return static::sendTemplate($subject, 'default', $params, 'deviser', $send_at);
	}

	public static function clientNewOrder(Order $order)
	{
		$subject = 'Congratulations, your purchase has been completed successfully!';

		$send_at = (new \DateTime())->format('Y-m-d H:i:s');

		$params = static::mergeCustomWithCommonVars(
			static::getOrderCommonVars($order),
			[
				'header' => 'Congratulations, your purchase has been completed successfully!',
				'hello' => 'Hello {{client_name}}',
				'text' => 'You purchased the following products from Todevise:',
				'text_2' => 'Total price w/shipping: '.$order->subtotal.'€',
				'text_3' => 'This is not an invoice. The invoice(s) for your order will be sent by the deviser(s), and you can download them from your Todevise profile by going to SETTINGS - MY ORDERS.<br />We will send you an email when the product(s) will be shipped.',
				'button_text' => 'GO TO MY PROFILE',
				'button_link' => '{{client_link_profile}}',
			]
		);

		return static::sendTemplate($subject, 'default', $params, 'client', $send_at);
	}

	public static function clientOrderShipped(Order $order, $packId)
	{
		$subject = 'Your products have been shipped!';

		$send_at = (new \DateTime())->format('Y-m-d H:i:s');

		$params = static::mergeCustomWithCommonVars(
			static::getOrderPackCommonVars($order, $packId),
			[
				'header' => 'Your products have been shipped!',
				'hello' => 'Hello {{client_name}}',
				'text' => 'The following products from {{deviser_name}} where shipped:',
				'text_2' => '
					<p>The shipping information is the following:</p>
					<p> - Shipping company: {{company}}</p>
					<p> - Tracking number: {{tracking_number}}</p>
					<p> - Tracking link: {{tracking_link}}</p>
				',
				'button_text' => 'GO TO MY PROFILE',
				'button_link' => '{{client_link_profile}}',
			]
		);

		return static::sendTemplate($subject, 'default', $params, 'client', $send_at);
	}

	public static function todeviseNewOrderReminder72(Order $order, $packId)
	{
		$subject = 'Order no aware after 72 hours!';

		$send_at = (new \DateTime())->add(\DateInterval::createFromDateString('72 hours'))->format('Y-m-d H:i:s');

		$params = static::mergeCustomWithCommonVars(
			static::getOrderPackCommonVars($order, $packId),
			[
				'header' => 'Order no aware after 72 hours!',
				'hello' => 'Hello Todevise',
				'text' => 'The deviser {{deviser_name}} did not click the button I’M AWARE for the order #{{order_number}}, with this products:',
				'text_2' => '',
				'button_text' => '',
				'button_link' => '',
			]
		);

		return static::sendTemplate($subject, 'default', $params, 'todevise', $send_at);
	}

	public static function todeviseOrderNoShippedReminder96(Order $order, $packId)
	{
		$subject = 'Order no shipped after 96 hours!';

		$send_at = (new \DateTime())->add(\DateInterval::createFromDateString('96 hours'))->format('Y-m-d H:i:s');

		$params = static::mergeCustomWithCommonVars(
			static::getOrderPackCommonVars($order, $packId),
			[
				'header' => 'Order no shipped after 96 hours!',
				'hello' => 'Hello Todevise',
				'text' => 'The deviser {{deviser_name}} did not fill in the shipping information for the order #{{order_number}}.',
				'text_2' => '',
				'button_text' => '',
				'button_link' => '',
				'products' => [],
			]
		);

		return static::sendTemplate($subject, 'default', $params, 'todevise', $send_at);
	}

	protected static function sendTemplate($subject, $template_name, $params, $destination_type, $send_at)
	{
		try {

			switch ($destination_type) {
				case 'deviser':
					$emailTo = $params['deviser_email'];
					$nameTo = $params['deviser_name'];
					break;
				case 'client':
					$emailTo = $params['client_email'];
					$nameTo = $params['client_name'];
					break;
				case 'todevise':
					$emailTo = 'info@todevise.com';
					$nameTo = 'Todevise';
					break;
				default:
					throw new Exception('Invalid destination type: '.$destination_type);
			}

			$merge_vars = [];
			foreach ($params as $name => $content) {
				$merge_vars[] = [
					'name' => $name,
					'content' => $content,
				];
			}

			$apiKey = \Yii::$app->params['mandrill_api_key'];
			$mandrill = new \Mandrill($apiKey);

			$message = [
				'subject' => 'TODEVISE - '.$subject,
				'from_email' => 'info@todevise.com',
				'from_name' => 'Todevise',
				'to' => [
					[
						'email' => $emailTo,
						'name' => $nameTo,
						'type' => 'to'
					]
				],
				'track_opens' => true,
				'track_clicks' => true,
				'auto_text' => true,
				'merge_language' => 'handlebars',
				'global_merge_vars' => $merge_vars,
				'merge_vars' => [],
			];
			$async = false;
			$ip_pool = null;
//			$send_at = (new \DateTime())->format('Y-m-d H:i:s');

			$result = $mandrill->messages->sendTemplate($template_name, [], $message, $async, $ip_pool, $send_at);

			return $result[0];

		} catch (\Mandrill_Error $e) {
			// Mandrill errors are thrown as exceptions
			echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
			throw $e;
		}
	}

	public static function cancelScheduled($id)
	{
		try {

			$apiKey = \Yii::$app->params['mandrill_api_key'];
			$mandrill = new \Mandrill($apiKey);

			$result = $mandrill->messages->cancelScheduled($id);

			return $result;

		} catch (\Mandrill_Unknown_Message $e) {

			return [];

		} catch (\Mandrill_Error $e) {
			// Mandrill errors are thrown as exceptions
			echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
			throw $e;
		}
	}

	public static function listScheduled()
	{
		try {

			$apiKey = \Yii::$app->params['mandrill_api_key'];
			$mandrill = new \Mandrill($apiKey);

			$result = $mandrill->messages->listScheduled();

			return $result;

		} catch (\Mandrill_Error $e) {
			// Mandrill errors are thrown as exceptions
			echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
			throw $e;
		}
	}

	public static function content($messageId)
	{
		try {

			$apiKey = \Yii::$app->params['mandrill_api_key'];
			$mandrill = new \Mandrill($apiKey);

			$result = $mandrill->messages->content($messageId);

			return $result;

		} catch (\Mandrill_Unknown_Message $e) {

			return null;

		} catch (\Mandrill_Error $e) {
			// Mandrill errors are thrown as exceptions
			echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
			throw $e;
		}
	}

	public static function listSent($dateFrom, $dateTo)
	{
		try {

			$apiKey = \Yii::$app->params['mandrill_api_key'];
			$mandrill = new \Mandrill($apiKey);

			$result = $mandrill->messages->search('*', $dateFrom, $dateTo);

			return $result;

		} catch (\Mandrill_Error $e) {
			// Mandrill errors are thrown as exceptions
			echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
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


	public static function test(Order $order, $packId)
	{
		die;
		try {
			$mandrill = new Mandrill('YOUR_API_KEY');
			$template_name = 'example template_name';
			$template_content = [
				[
					'name' => 'example name',
					'content' => 'example content'
				]
			];
			$message = [
				'html' => '<p>Example HTML content</p>',
				'text' => 'Example text content',
				'subject' => 'example subject',
				'from_email' => 'message.from_email@example.com',
				'from_name' => 'Example Name',
				'to' => [
					[
						'email' => 'recipient.email@example.com',
						'name' => 'Recipient Name',
						'type' => 'to'
					]
				],
				'headers' => ['Reply-To' => 'message.reply@example.com'],
				'important' => false,
				'track_opens' => null,
				'track_clicks' => null,
				'auto_text' => null,
				'auto_html' => null,
				'inline_css' => null,
				'url_strip_qs' => null,
				'preserve_recipients' => null,
				'view_content_link' => null,
				'bcc_address' => 'message.bcc_address@example.com',
				'tracking_domain' => null,
				'signing_domain' => null,
				'return_path_domain' => null,
				'merge' => true,
				'merge_language' => 'mailchimp',
				'global_merge_vars' => [
					[
						'name' => 'merge1',
						'content' => 'merge1 content'
					]
				],
				'merge_vars' => [
					[
						'rcpt' => 'recipient.email@example.com',
						'vars' => [
							[
								'name' => 'merge2',
								'content' => 'merge2 content'
							]
						]
					]
				],
				'tags' => ['password-resets'],
				'subaccount' => 'customer-123',
				'google_analytics_domains' => ['example.com'],
				'google_analytics_campaign' => 'message.from_email@example.com',
				'metadata' => ['website' => 'www.example.com'],
				'recipient_metadata' => [
					[
						'rcpt' => 'recipient.email@example.com',
						'values' => ['user_id' => 123456]
					]
				],
				'attachments' => [
					[
						'type' => 'text/plain',
						'name' => 'myfile.txt',
						'content' => 'ZXhhbXBsZSBmaWxl'
					]
				],
				'images' => [
					[
						'type' => 'image/png',
						'name' => 'IMAGECID',
						'content' => 'ZXhhbXBsZSBmaWxl'
					]
				]
			];
			$async = false;
			$ip_pool = 'Main Pool';
			$send_at = 'example send_at';
			$result = $mandrill->messages->sendTemplate($template_name, $template_content, $message, $async, $ip_pool,
				$send_at);
			print_r($result);
			/*
			Array
			(
				[0] => Array
					(
						[email] => recipient.email@example.com
						[status] => sent
						[reject_reason] => hard-bounce
						[_id] => abc123abc123abc123abc123abc123
					)

			)
			*/
		} catch (Mandrill_Error $e) {
			// Mandrill errors are thrown as exceptions
			echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
			throw $e;
		}
	}
}