<?php

namespace app\controllers;

use app\helpers\CController;
use app\models\Order;
use app\models\Person;
use Stripe\Account;
use Stripe\Stripe;
use Yii;
use yii\base\Exception;
use yii\helpers\Url;
use yii\web\NotFoundHttpException;


class StripeController extends CController
{
	/**
	 * @inheritdoc
	 */
	public function beforeAction($action)
	{
		if ($action->id == 'paid') {
			$this->enableCsrfValidation = false;
		}

		return parent::beforeAction($action);
	}

	public function actionConnectButton()
	{
		$html = '<a href="https://connect.stripe.com/oauth/authorize?response_type=code&client_id='.\Yii::$app->params['stripe_client_id'].'&scope=read_write">Connect with stripe</a>';
		echo $html;
	}

	public function actionConnectBack()
	{
		$person_id = Yii::$app->session->get('person_id_stripe_connection');
		if (empty($person_id)) {
			throw new \Exception("Missing required person identifier");
		}
		Yii::$app->session->remove('person_id_stripe_connection');

		$person = Person::findOneSerialized($person_id);
		if (!$person->isDeviser() || !$person->isDeviserEditable()) {
			throw new NotFoundHttpException();
		}

		if (isset($_GET['code'])) {

			// Redirect w/ code
			$code = $_GET['code'];

			if ($person->settingsMapping->stripeInfoMapping->access_token) {
				// Disconnect previous account connected
				$apiKey = \Yii::$app->params['stripe_secret_key'];
				$clientId = \Yii::$app->params['stripe_client_id'];
				$stripeUserId = $person->settingsMapping->stripeInfoMapping->stripe_user_id;

				$curl = curl_init();
				curl_setopt_array($curl, [
					CURLOPT_RETURNTRANSFER => 1,
					CURLOPT_URL => 'https://connect.stripe.com/oauth/deauthorize',
					CURLOPT_POST => 1,
					CURLOPT_USERPWD => $apiKey . ':',
					CURLOPT_POSTFIELDS => http_build_query([
						'client_id' => $clientId,
						'stripe_user_id' => $stripeUserId,
					])
				]);
				$resp = curl_exec($curl);
				curl_close($curl);
			}

			$token_request_body = [
				'grant_type' => 'authorization_code',
				'client_id' => Yii::$app->params['stripe_client_id'],
				'code' => $code,
				'client_secret' => Yii::$app->params['stripe_secret_key'],
			];

			$req = curl_init('https://connect.stripe.com/oauth/token');
			curl_setopt($req, CURLOPT_RETURNTRANSFER, true);
			curl_setopt($req, CURLOPT_POST, true);
			curl_setopt($req, CURLOPT_POSTFIELDS, http_build_query($token_request_body));

			// TODO: Additional error handling
			$respCode = curl_getinfo($req, CURLINFO_HTTP_CODE);
			$resp = json_decode(curl_exec($req), true);
			curl_close($req);

			// Save current connect info
			$person->settingsMapping->stripe_info = $resp;
			$person->save();

			$this->redirect(Url::to(['settings/billing', 'slug' => $person->slug, 'person_id' => $person->short_id]));


		} else if (isset($_GET['error'])) {

			// Error
			echo $_GET['error_description'];

		} else {

			// Show OAuth link
			$authorize_request_body = [
				'response_type' => 'code',
				'scope' => 'read_write',
				'client_id' => Yii::$app->params['stripe_client_id'],
			];

			$url = 'https://connect.stripe.com/oauth/authorize' . '?' . http_build_query($authorize_request_body);
			echo "<a href='$url'>Connect with Stripe</a>";

		}
	}

	public function actionCheckoutTest($order_id)
	{
		$order = Order::findOneSerialized($order_id);
		/* @var Order $order */
		$order->order_state = Order::ORDER_STATE_CART;
		$order->save();

		$html = '
			<form action="/stripe/receive-payment/' . $order_id . '" method="POST">
			  <script
				src="https://checkout.stripe.com/checkout.js" class="stripe-button"
				data-key="pk_test_p1DPyiicE2IerEV676oj5t89"
				data-amount="' . ($order->subtotal * 100) . '"
				data-name="Todevise"
				data-description="Order Nº ' . $order_id . '"
				data-image="' . Url::base(true) . '/imgs/logo.png"
				data-locale="auto"
				data-zip-code="true"
				data-currency="eur">
			  </script>
			  <input type="hidden" name="order_id" value="' . $order_id . '"/>
			</form>';
		echo $html;
	}

	public function actionReceivePayment($order_id)
	{

		Stripe::setApiKey(Yii::$app->params['stripe_secret_key']);

		$token = Yii::$app->request->post('stripeToken');

		$order = Order::findOneSerialized($order_id);
		/* @var Order $order */
		if ($order) {

			try {

				if ($order->order_state != Order::ORDER_STATE_CART) {
					throw new Exception("This order is in an invalid state");
				}

				$customer = \Stripe\Customer::create([
					'email' => $order->clientInfoMapping->email,
					'source' => $token,
				]);

				$charge = \Stripe\Charge::create([
					'customer' => $customer->id,
					'currency' => 'eur',
					'amount' => $order->subtotal * 100,
					"description" => "Order Nº " . $order->short_id,
					"metadata" => [
						"order_id" => $order->short_id,
						"order" => json_encode($order),
					],
				]);

				$order->order_state = Order::ORDER_STATE_PAID;
				$order->save();

				$message = 'Your purchase is complete';

			} catch (\Exception $e) {

				$order->order_state = Order::ORDER_STATE_UNPAID;
				$order->save();

				$message = 'Error processing your charge: ' . $e->getMessage();

			}


		} else {
			$message = 'Order ' . $order_id . ' not found';
		}

		echo $message;
	}

	public function actionTestTransfer()
	{

		Stripe::setApiKey(Yii::$app->params['stripe_secret_key']);

		$deviser = Person::findOneSerialized('51fb4b9');
		/* @var Order $order */
		if ($deviser) {

			try {

				$result = \Stripe\Balance::retrieve();
				echo '<pre>'.$result.'</pre>';

				$result = \Stripe\Transfer::create(array(
					'amount' => 100,
					'currency' => "eur",
					'destination' => $deviser->settingsMapping->stripeInfoMapping->stripe_user_id,
					'description' => 'Transfer 1000€ from todevise to test account',
				));

				echo '<pre>'.$result.'</pre>';

				var_dump($result);

				$message = 'Transfer completed';

			} catch (\Exception $e) {

				$message = 'Error processing your charge: ' . $e->getMessage();

			}


		} else {
			$message = 'Deviser not found';
		}

		echo $message;
	}

	public function actionTestConnect()
	{
		$stripe = array(
			"secret_key"      => Yii::$app->params['stripe_secret_key'],
			"publishable_key"      => Yii::$app->params['stripe_publishable_key'],
		);

		Stripe::setApiKey(Yii::$app->params['stripe_secret_key']);

		$deviser = Person::findOneSerialized('13cc33k');

		$personalInfo = $deviser->personalInfoMapping;
		$bankInfo = $deviser->settingsMapping->bankInfoMapping;
		var_dump($bankInfo);
		die;

		// Create account
//		$data = [
//			"managed" => true,
//			"country" => 'US',
//		];
//		$account = \Stripe\Account::create($data);
//
//		echo '<pre>'.$account.'</pre>';
//		echo '<pre>'.$account->id.'</pre>';

		// Add info required by stripe
		$account = Account::retrieve('acct_19iLt1CyjDrMHgCT');
		$account->external_account = [
			"object" => "bank_account",
			"country" => "US",
			"currency" => "usd",
			"routing_number" => $bankInfo->routing_number,
			"account_number" => $bankInfo->account_number,
		];

		if ($personalInfo->bday) {
			$bday = $personalInfo->bday->toDateTime();
		} else {
			$bday = \DateTime::createFromFormat('Y-m-d', '1984-07-09');
		}
		$account->legal_entity->dob = [
			'day' => $bday->format('d'),
			'month' => $bday->format('m'),
			'year' => $bday->format('Y'),
		];

		$account->legal_entity->first_name = $personalInfo->name;
		$account->legal_entity->last_name = $personalInfo->brand_name;
		$account->legal_entity->type = "individual";

		$account->tos_acceptance = [
			"date" => time(),
			"ip" => '83.63.169.121',
		];

		$result = $account->save();
		echo '<pre>'.print_r($result, true).'</pre>';


		// More info required by stripe
//		$account = Account::retrieve('acct_19hnAyJcOYA1HBwM');
//
//		$account->legal_entity->address = [
//			'city' => 'Logan',
//			'line1' => '1299, Shakertown Rd, Rockfield, Logan',
//			'postal_code' => '42274',
//			'state' => 'Kentucky',
//			'country' => 'US'
//		];
//		$account->legal_entity->ssn_last_4 = '1234';
//
//		$result = $account->save();
//		echo '<pre>'.print_r($result, true).'</pre>';

		$account = Account::retrieve('acct_19hnAyJcOYA1HBwM');

		$account->legal_entity->personal_id_number = '567891234';

		$result = $account->save();
		echo '<pre>'.print_r($result, true).'</pre>';

	}
}




//		/*
//		"id": "acct_19hnAyJcOYA1HBwM",
//		"object": "account",
//		"business_logo": null,
//		"business_name": null,
//		"business_url": null,
//		"charges_enabled": true,
//		"country": "US",
//		"debit_negative_balances": false,
//		"decline_charge_on": {
//				"avs_failure": false,
//			"cvc_failure": false
//		},
//		"default_currency": "usd",
//		"details_submitted": false,
//		"display_name": null,
//		"email": null,
//		"external_accounts": {
//				"object": "list",
//			"data": [
//				{
//					"id": "ba_19hnAyJcOYA1HBwM0L46AOSI",
//					"object": "bank_account",
//					"account": "acct_19hnAyJcOYA1HBwM",
//					"account_holder_name": null,
//					"account_holder_type": null,
//					"bank_name": "JPMORGAN CHASE",
//					"country": "US",
//					"currency": "usd",
//					"default_for_currency": true,
//					"fingerprint": "Z1aQQjK6zafBOjXK",
//					"last4": "6789",
//					"metadata": [],
//					"routing_number": "021000021",
//					"status": "new"
//				}
//			],
//			"has_more": false,
//			"total_count": 1,
//			"url": "\/v1\/accounts\/acct_19hnAyJcOYA1HBwM\/external_accounts"
//		},
//		"keys": {
//				"secret": "sk_test_2xTlFQBEs7w17wllbtlZrzJx",
//			"publishable": "pk_test_LnvDcf9EJrA4vyldlwXgCWro"
//		},
//		"legal_entity": {
//				"address": {
//					"city": null,
//				"country": "US",
//				"line1": null,
//				"line2": null,
//				"postal_code": null,
//				"state": null
//			},
//			"business_name": null,
//			"business_tax_id_provided": false,
//			"dob": {
//					"day": null,
//				"month": null,
//				"year": null
//			},
//			"first_name": null,
//			"last_name": null,
//			"personal_address": {
//					"city": null,
//				"country": "US",
//				"line1": null,
//				"line2": null,
//				"postal_code": null,
//				"state": null
//			},
//			"personal_id_number_provided": false,
//			"ssn_last_4_provided": false,
//			"type": null,
//			"verification": {
//					"details": null,
//				"details_code": null,
//				"document": null,
//				"status": "unverified"
//			}
//		},
//		"managed": true,
//		"metadata": [],
//		"product_description": null,
//		"statement_descriptor": null,
//		"support_email": null,
//		"support_phone": null,
//		"timezone": "Etc\/UTC",
//		"tos_acceptance": {
//				"date": 1485813538,
//			"ip": "83.63.169.121",
//			"user_agent": null
//		},
//		"transfer_schedule": {
//				"delay_days": 2,
//			"interval": "daily"
//		},
//		"transfer_statement_descriptor": null,
//		"transfers_enabled": true,
//		"verification": {
//			"disabled_reason": null,
//			"due_by": null,
//			"fields_needed": [
//					"legal_entity.dob.day",
//					"legal_entity.dob.month",
//					"legal_entity.dob.year",
//					"legal_entity.first_name",
//					"legal_entity.last_name",
//					"legal_entity.type"
//				]
//			}
//		}
//
//
//
// client_id: ca_9z47cPhqGcOPdRgTMEOXnF3hc7Cwf59g