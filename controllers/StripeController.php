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

		Stripe::setApiKey('sk_test_eLdJxVmKSGQxGPhX2bqpoRk4');

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

	public function actionTestConnect()
	{
		$stripe = array(
			"secret_key"      => "sk_test_eLdJxVmKSGQxGPhX2bqpoRk4",
			"publishable_key" => "pk_test_p1DPyiicE2IerEV676oj5t89"
		);

		Stripe::setApiKey('sk_test_eLdJxVmKSGQxGPhX2bqpoRk4');

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