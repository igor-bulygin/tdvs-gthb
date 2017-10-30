<?php
namespace app\helpers;

use app\models\Order;
use yii\base\Exception;

class EmailsHelper
{
	public static function setOrderPaidNotifications(Order $order)
	{
		$packs = $order->getPacks();
		foreach ($packs as $pack) {
			static::sendDeviserOrderNotification($order, $pack->short_id);
		}
	}

	protected static function sendDeviserOrderNotification(Order $order, $packId)
	{
		try {
			$apiKey = \Yii::$app->params['mandrill_api_key'];
			$mandrill = new \Mandrill($apiKey);

			$pack = $order->getPack($packId);
			if (empty($pack)) {
				throw new Exception(sprintf('Pack with id %s does not exists in the order', $packId));
			}
			$deviser = $pack->getDeviser();
			$deviserEmail = $deviser->credentials['email'];
			$deviserName = $deviser->getName();
			$deviserProfileLink = $deviser->getSettingsLink('open-orders');

			$deviserEmail = 'yachar1@gmail.com';
			$deviserName = 'Jose';

			$client = $order->getPerson();
			$clientName = $client->getName();

			$productsVars = [];
			$products = $pack->getProducts();
			foreach ($products as $packProduct) {
				$product = $packProduct->getProduct();
				$productsVars[] = [
					'name' => $product->getName(),
					'quantity' => $packProduct->quantity,
					'total' => $packProduct->price * $packProduct->quantity . '€',
				];
			}

			$message = [
				'html' => '
					<h1>YOU HAVE A NEW SALE</h1>
					<p>{{client_name}} bought the following products:</p>
					<ul>
						<li style="list-style: none">{{#products}}</li>
						<li>{{this.quantity}} x {{this.name}}: {{this.total}}</li>
						<li style="list-style: none">{{/products}}</li>
					</ul>
					<p>Please log into your Todevise profile and let us know that you are aware of the sale (even if you are not ready to send it yet, we need to know that you saw the notification).</p>
					<a href="{{link_profile}}">GO TO PROFILE</a>',

				'subject' => 'YOU HAVE A NEW SALE',
				'from_email' => 'info@todevise.com',
				'from_name' => 'Todevise',
				'to' => [
					[
						'email' => $deviserEmail,
						'name' => $deviserName,
						'type' => 'to'
					]
				],
				'track_opens' => true,
				'track_clicks' => true,
				'auto_text' => true,
				'merge_language' => 'handlebars',
				'global_merge_vars' => [
					[
						'name' => 'client_name',
						'content' => $clientName,
					],
					[
						'name' => 'link_profile',
						'content' => $deviserProfileLink,
					],
					[
						'name' => 'products',
						'content' => $productsVars,
					],
				],
				'merge_vars' => [],
			];
			$async = false;
			$ip_pool = null;
			$send_at = date('Y-m-d H:i:s');

			$result = $mandrill->messages->send($message, $async, $ip_pool, $send_at);

			echo '<pre>' . print_r($result, true) . '</pre><br />';

		} catch (\Mandrill_Error $e) {
			// Mandrill errors are thrown as exceptions
			echo 'A mandrill error occurred: ' . get_class($e) . ' - ' . $e->getMessage();
			// A mandrill error occurred: Mandrill_Unknown_Subaccount - No subaccount exists with the id 'customer-123'
			throw $e;
		}
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