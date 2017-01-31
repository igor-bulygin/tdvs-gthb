<?php

namespace app\controllers;

use app\helpers\CController;
use app\models\Order;
use app\models\Person;
use Stripe\Account;
use Stripe\Stripe;
use Yii;
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

	public function actionIndex()
	{
		$stripe = array(
			"secret_key"      => "sk_test_eLdJxVmKSGQxGPhX2bqpoRk4",
			"publishable_key" => "pk_test_p1DPyiicE2IerEV676oj5t89"
		);

		Stripe::setApiKey('sk_test_eLdJxVmKSGQxGPhX2bqpoRk4');

		$deviser = Person::findOneSerialized('13cc33k');

		$personalInfo = $deviser->personalInfoMapping;
		$bankInfo = $deviser->settingsMapping->bankInfoMapping;

//		$data = [
//			"managed" => true,
//			"country" => 'US',
//			"external_account" => [
//				"object" => "bank_account",
//				"country" => "US",
//				"currency" => "usd",
//				"routing_number" => $bankInfo->routing_number,
//				"account_number" => $bankInfo->account_number,
//			],
//			"tos_acceptance" => [
//				"date" => time(),
//				"ip" => '83.63.169.121',
//			]
//		];
//		$account = \Stripe\Account::create($data);
//
//		echo '<pre>'.$account.'</pre>';
//		echo '<pre>'.$account->id.'</pre>';
//


//		$account = Account::retrieve('acct_19hnAyJcOYA1HBwM');
//
//
//		if ($personalInfo->bday) {
//			$bday = $personalInfo->bday->toDateTime();
//		} else {
//			$bday = \DateTime::createFromFormat('Y-m-d', '1984-07-09');
//		}
//		$account->legal_entity->dob = [
//			'day' => $bday->format('d'),
//			'month' => $bday->format('m'),
//			'year' => $bday->format('Y'),
//		];
//
//
//		$account->legal_entity->first_name = $personalInfo->name;
//		$account->legal_entity->last_name = $personalInfo->brand_name;
//		$account->legal_entity->type = "individual";
//
//		$result = $account->save();
//		echo '<pre>'.print_r($result, true).'</pre>';

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

	public function actionCheckout() {
		$html = '
			<form action="/stripe/paid" method="POST">
			  <script
				src="https://checkout.stripe.com/checkout.js" class="stripe-button"
				data-key="pk_test_p1DPyiicE2IerEV676oj5t89"
				data-amount="999"
				data-name="Todevise"
				data-description="Lorem ipsum dolor sit amet"
				data-image="'.Url::base(true).'/imgs/logo.png"
				data-locale="auto"
				data-zip-code="true"
				data-currency="eur">
			  </script>
			  <input type="hidden" name="order_id" value="1234"/>
			</form>';
		echo $html;
	}

	public function actionPaid() {

		Stripe::setApiKey('sk_test_eLdJxVmKSGQxGPhX2bqpoRk4');

		$order_id = Yii::$app->request->post('order_id');
		$token = Yii::$app->request->post('stripeToken');

		$order = Order::findOneSerialized($order_id); /* @var Order $order */
		if ($order) {

			$customer = \Stripe\Customer::create([
				'email' => $order->clientInfoMapping->email,
				'source' => $token,
			]);

			$charge = \Stripe\Charge::create([
				'customer' => $customer->id,
				'currency' => 'usd',
				'amount' => $order->subtotal,
				"description" => "Order Nº ".$order->short_id,
				"metadata" => [
					"order_id" => $order->id,
					"order" => $order,
				],
			]);

			$order->order_state = Order::ORDER_STATE_PAID;
			$order->save();

			echo '<h1>Successfully charged '.$order->subtotal.'</h1>';
		} else {
			echo '<h1>Order '.$order_id.' not found</h1>';
		}
	}

	public function moreTest() {

		Stripe::setApiKey('sk_test_eLdJxVmKSGQxGPhX2bqpoRk4');

		$deviser = Person::findOneSerialized('13cc33k');

		$account = Account::create([
			"country" => $deviser->personalInfoMapping->country,
			"managed" => true,
			"email" => $deviser->credentials['email'],
		]);

		$account = Account::retrieve($account->id);

		// $account->support_phone = '555-666-7777';
		if ($deviser->personalInfoMapping->bday) {
			$bday = $deviser->personalInfoMapping->bday->toDateTime();
			$account->legal_entity->dob = [
				'day' => $bday->format('dd'),
				'month' => $bday->format('mm'),
				'year' => $bday->format('yyyy'),
			];
		}

//		$account->legal_entity->address = [
//			'city' => 'San Clemente',
//			'line1' => '100',
//			'line2'=>'Avenida Presidio',
//			'postal_code' => '92672',
//			'state' => 'CA',
//			'country' => 'US'
//		];

		$account->legal_entity->ssn_last_4 = '4242';
		$account->legal_entity->first_name = $deviser->personalInfoMapping->name;
		$account->legal_entity->last_name = $deviser->personalInfoMapping->last_name;
		$account->legal_entity->type = "individual";
		$account->tos_acceptance = [
			'date' => time(),
			'ip' =>  $_SERVER['REMOTE_ADDR']
		];

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