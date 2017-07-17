<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Order;
use app\models\OrderPersonInfo;
use app\models\OrderProduct;
use app\models\Person;
use Stripe\Stripe;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class CartController extends AppPublicController
{

	public function actionCreateCart()
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
		$cart = new Order();
		if (!Yii::$app->user->isGuest) {
			$cart->person_id = Yii::$app->user->identity->short_id;
		}
		$cart->subtotal = 0;
		$cart->save();

		Yii::$app->response->setStatusCode(201); // Created

		return $cart;
	}

	public function actionView($cartId)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
		$cart = Order::findOneSerialized($cartId);

		if (empty($cart)) {
			throw new NotFoundHttpException();
		}

		if ($cart->order_state != Order::ORDER_STATE_CART) {
			throw new BadRequestHttpException();
		}

		if (!Yii::$app->user->isGuest) {
			if ($cart->person_id != Yii::$app->user->identity->short_id) {
				throw new ForbiddenHttpException();
			}
		} else {
			if (!empty($cart->person_id)) {
				throw new ForbiddenHttpException();
			}
		}
		Yii::$app->response->setStatusCode(200); // Ok

		return $cart;
	}

	public function actionAddProduct($cartId)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
		$cart = Order::findOneSerialized($cartId);
		/* @var Order $cart */

		if (empty($cart)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}
		$product = new OrderProduct();
		$product->setParentObject($cart);

		if ($product->load(Yii::$app->request->post(), '') && $product->validate()) {

			$cart->addProduct($product);
			$cart->save();

			Yii::$app->response->setStatusCode(201); // Created

			return $cart;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request

			return ["errors" => $product->errors];
		}
	}

	public function actionDeleteProduct($cartId, $priceStockId)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
		$cart = Order::findOneSerialized($cartId);
		/* @var Order $cart */
		if (empty($cart)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		$cart->deleteProduct($priceStockId);
		$cart->save();

		Yii::$app->response->setStatusCode(200); // Ok

		return $cart;
	}


	public function actionPersonInfo($cartId)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
		$cart = Order::findOneSerialized($cartId);
		/* @var Order $cart */

		if (empty($cart)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		$personInfo = new OrderPersonInfo();
		$personInfo->setParentObject($cart);

		if ($personInfo->load(Yii::$app->request->post(), '') && $personInfo->validate()) {

			$cart->personInfoMapping = $personInfo;
			$cart->save();

			Yii::$app->response->setStatusCode(200); // Created

			return $cart;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request

			return ["errors" => $personInfo->errors];
		}
	}


	public function actionReceiveToken($cartId)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
		$cart = Order::findOneSerialized($cartId);
		/* @var Order $cart */

		if (empty($cart)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}
		/*
		{
		  "id": "tok_19igOXJt4mveficFYDqFqBcB",
		  "object": "token",
		  "card": {
			"id": "card_19igOXJt4mveficFdxIMWpw9",
			"object": "card",
			"address_city": null,
			"address_country": null,
			"address_line1": null,
			"address_line1_check": null,
			"address_line2": null,
			"address_state": null,
			"address_zip": "15177",
			"address_zip_check": "pass",
			"brand": "Visa",
			"country": "US",
			"cvc_check": "pass",
			"dynamic_last4": null,
			"exp_month": 5,
			"exp_year": 2020,
			"funding": "credit",
			"last4": "4242",
			"metadata": {},
			"name": "jose.vazquez.viader@gmail.com",
			"tokenization_method": null
		  },
		  "client_ip": "83.63.169.121",
		  "created": 1486025805,
		  "email": "jose.vazquez.viader@gmail.com",
		  "livemode": false,
		  "type": "card",
		  "used": false
		}
		*/

		$currentPaymentInfo = Yii::$app->request->post('token');
		$token = $currentPaymentInfo['id'];

		if (!$token) {
			$message = sprintf("We didn't received any token from stripe for cart_id %s", $cartId);
			Yii::info($message, 'Stripe');
			throw new BadRequestHttpException("No token received from stripe");
		}

		if ($cart->order_state != Order::ORDER_STATE_CART) {
			throw new BadRequestHttpException("This order is in an invalid state");
		}

		try {

			Stripe::setApiKey(Yii::$app->params['stripe_secret_key']);

			$products = $cart->productsMapping;

			// Create an array of devisers, with amount price for each
			$devisers = [];
			foreach ($products as $product) {
				if (isset($devisers[$product->deviser_id])) {
					$amount = $devisers[$product->deviser_id]['amount'];
				} else {
					$amount = 0;
				}
				$devisers[$product->deviser_id] = [
					'amount' => $amount + ($product->price * $product->quantity),
					'deviser' => Person::findOneSerialized($product->deviser_id),
				];
			}

			// Create a customer in stripe for the received token
			$customer = \Stripe\Customer::create([
				'email' => $cart->personInfoMapping->email,
				'source' => $token,
			]);

			$charges = [];
			foreach ($devisers as $oneDeviser) {
				$amount = $oneDeviser['amount'];
				$deviser = $oneDeviser['deviser'];
				/* @var Person $deviser */

				$stripeAmount = (int)($amount * 100);
				$todeviseFee = (int)($stripeAmount * Yii::$app->params['default_todevise_fee']);

				if (empty($deviser->settingsMapping->stripeInfoMapping->access_token)) {

					// If the deviser has no a connected stripe account, we charge directly to todevise account
					$charges[] = \Stripe\Charge::create([
						'customer' => $customer->id,
						'currency' => 'eur',
						'amount' => $stripeAmount,
						"description" => "Order Nº " . $cart->short_id,
						"metadata" => [
							"order_id" => $cart->short_id,
							"order" => json_encode($cart),
						],
					]);

				} else {

					// Create a Token from the existing customer on the deviser stripe account
					$token = \Stripe\Token::create(
						[
							"customer" => $customer->id,
							"card" => $currentPaymentInfo['card']['id'],
						],
						["stripe_account" => $deviser->settingsMapping->stripeInfoMapping->stripe_user_id] // id of the connected account
					);

					// Create a charge for this deviser
					$charges[] = \Stripe\Charge::create(
						[
							'source' => $token,
							'currency' => 'eur',
							'amount' => $stripeAmount,
							"description" => "Order Nº " . $cart->short_id,
							'application_fee' => $todeviseFee,
							"metadata" => [
								"order_id" => $cart->short_id,
							],
						],
						[
							'stripe_account' => $deviser->settingsMapping->stripeInfoMapping->stripe_user_id,
						]
					);
				}
			}

			// Save charges responses and payment_info in the order
			$cart->setAttribute('payment_info', $currentPaymentInfo);
			$cart->setAttribute('charges', json_encode($charges));
			$cart->order_state = Order::ORDER_STATE_PAID;
			$cart->save();

			$cart->composeEmailOrderPaid(true);

			Yii::$app->response->setStatusCode(200); // Created

			return $cart;
		} catch (\Exception $e) {
			$message = sprintf("Error in receive-token: " . $e->getMessage());
			Yii::info($message, 'Stripe');

			throw $e;
		}
	}
}