<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Order;
use app\models\OrderProduct;
use Stripe\Stripe;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class CartController extends AppPublicController
{

	public function actionCreateCart()
	{
		$cart = new Order();
		if (!Yii::$app->user->isGuest) {
			$cart->person_id = Yii::$app->user->identity->short_id;
		}
		$cart->subtotal = 0;
		$cart->save();

		Yii::$app->response->setStatusCode(201); // Created

		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
		$cart = Order::findOneSerialized($cart->short_id);
		$cart->setSubDocumentsForSerialize();
		return $cart;
	}

	public function actionView($cartId)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
		$cart = Order::findOneSerialized($cartId);

		if (empty($cart)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		if (!$cart->isCart()) {
			throw new BadRequestHttpException("This order is in an invalid state");
		}

		if (!Yii::$app->user->isGuest) {
			if (empty($cart->person_id)) {
				$cart->person_id = Yii::$app->user->identity->short_id;
				$cart->save();
			} elseif ($cart->person_id != Yii::$app->user->identity->short_id) {
				throw new ForbiddenHttpException();
			}
		} else {
			if (!empty($cart->person_id)) {
				throw new ForbiddenHttpException();
			}
		}
		Yii::$app->response->setStatusCode(200); // Ok

		$cart->setSubDocumentsForSerialize();
		return $cart;
	}

	public function actionAddProduct($cartId)
	{
		$cart = Order::findOneSerialized($cartId);
		/* @var Order $cart */

		if (empty($cart)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		if (!$cart->isCart()) {
			throw new BadRequestHttpException("This order is in an invalid state");
		}

		if (!$cart->isCartEditable()) {
			throw new UnauthorizedHttpException();
		}

		$product = new OrderProduct();
		$product->setParentObject($cart);

		if ($product->load(Yii::$app->request->post(), '') && $product->validate()) {

			$cart->addProduct($product);

			Yii::$app->response->setStatusCode(201); // Created

			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
			$cart = Order::findOneSerialized($cartId);
			$cart->setSubDocumentsForSerialize();
			return $cart;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request

			return ["errors" => $product->errors];
		}
	}

	public function actionDeleteProduct($cartId, $priceStockId)
	{
		$cart = Order::findOneSerialized($cartId);
		/* @var Order $cart */
		if (empty($cart)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		if (!$cart->isCart()) {
			throw new BadRequestHttpException("This order is in an invalid state");
		}

		if (!$cart->isCartEditable()) {
			throw new UnauthorizedHttpException();
		}

		$cart->deleteProduct($priceStockId);

		Yii::$app->response->setStatusCode(200); // Ok

		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
		$cart = Order::findOneSerialized($cartId);
		$cart->setSubDocumentsForSerialize();
		return $cart;
	}

	public function actionUpdate($cartId)
	{
		$cart = Order::findOneSerialized($cartId);
		if (!$cart) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		if (!$cart->isCart()) {
			throw new BadRequestHttpException("This order is in an invalid state");
		}

		if (!$cart->isCartEditable()) {
			throw new UnauthorizedHttpException();
		}

		// only validate received fields (only if we are not changing the state)
		$cart->setScenario(Order::SCENARIO_CART);

		// In this method, we only update the next properties:
		// - shipping_address
		// - billing_address
		// - packs.shipping_type

		$post = Yii::$app->request->post();
		if (isset($post['shipping_address'])) {
			$cart->shipping_address = $post['shipping_address'];
		}
		if (isset($post['billing_address'])) {
			$cart->billing_address = $post['billing_address'];
		}
		if (isset($post['packs'])) {
			$packs = $cart->getPacks();
			foreach ($post['packs'] as $packPost) {
				if (isset($packPost['short_id']) && isset($packPost['shipping_type'])) {
					foreach ($packs as $packActual) {
						if ($packActual->short_id == $packPost['short_id']) {
							$packActual->shipping_type = $packPost['shipping_type'];
						}
					}
				}
			}
			$cart->setPacks($packs);
		}

		if ($cart->validate()) {

			$cart->save(false);

			Yii::$app->response->setStatusCode(200); // Ok

			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
			$cart = Order::findOneSerialized($cartId);
			$cart->setSubDocumentsForSerialize();
			return $cart;

		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $cart->errors];
		}
	}

	public function actionReceiveToken($cartId)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
		$cart = Order::findOneSerialized($cartId);
		/* @var Order $cart */

		if (empty($cart)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		if (!$cart->isCart()) {
			throw new BadRequestHttpException("This order is in an invalid state");
		}

		if (!$cart->isCartEditable()) {
			throw new UnauthorizedHttpException();
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

		if (!$cart->isCart()) {
			throw new BadRequestHttpException("This order is in an invalid state");
		}

		try {

			Stripe::setApiKey(Yii::$app->params['stripe_secret_key']);

			$person = $cart->getPerson();

			$packs = $cart->getPacks();

			$shippingAddress = $cart->getShippingAddress();
			$billingAddress = $cart->getBillingAddress();

			// TODO: check if customer already exists in stripe

			// Create a customer in stripe for the received token
			$customer = \Stripe\Customer::create([
				'email' => $person->credentials['email'],
				'business_vat_id' => $billingAddress->vat_id,
				'shipping' => [
					'address' => [
						'line1' => $shippingAddress->address,
						'city' => $shippingAddress->city,
						'country' => $shippingAddress->country,
						'postal_code' => $shippingAddress->zipcode,
					],
					'name' => $shippingAddress->getFullName(),
					'phone' => $shippingAddress->getPhone(),
				],
				'source' => $token,
			]);

			$charges = [];
			foreach ($packs as $pack) {
				$deviser = $pack->getDeviser();

				$stripeAmount = (int)(($pack->pack_price + $pack->shipping_price) * 100);
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
//			$cart->setAttribute('payment_info', $currentPaymentInfo);
//			$cart->setAttribute('charges', $charges);
			$cart->order_state = Order::ORDER_STATE_PAID;
			$cart->save();

			$cart->composeEmailOrderPaid(true);

			Yii::$app->response->setStatusCode(200); // Created

			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
			$cart = Order::findOneSerialized($cartId);
			$cart->setSubDocumentsForSerialize();
			return $cart;
		} catch (\Exception $e) {
			$message = sprintf("Error in receive-token: " . $e->getMessage());
			Yii::info($message, 'Stripe');

			throw $e;
		}
	}
}