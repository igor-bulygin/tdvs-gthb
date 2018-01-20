<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Order;
use app\models\OrderPack;
use app\models\OrderProduct;
use Stripe\Stripe;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;
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
			throw new ConflictHttpException("This order is in an invalid state");
		}

		$cart->checkOwnerAndTryToAssociate();

		if (!$cart->isEditable()) {
			throw new UnauthorizedHttpException("You have no access to this order");
		}

		$cart->recalculateAll();
		$cart->save();

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
			throw new ConflictHttpException("This order is in an invalid state");
		}

		$cart->checkOwnerAndTryToAssociate();

		if (!$cart->isEditable()) {
			throw new UnauthorizedHttpException("You have no access to this order");
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
			throw new ConflictHttpException("This order is in an invalid state");
		}

		$cart->checkOwnerAndTryToAssociate();

		if (!$cart->isEditable()) {
			throw new UnauthorizedHttpException("You have no access to this order");
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
			throw new ConflictHttpException("This order is in an invalid state");
		}

		$cart->checkOwnerAndTryToAssociate();

		if (!$cart->isEditable()) {
			throw new UnauthorizedHttpException("You have no access to this order");
		}

		// only validate received fields (only if we are not changing the state)
		$cart->setScenario(Order::SCENARIO_CART);

		// Get person associated with the cart
		$person = $cart->getPerson();

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
							$packActual->recalculateTotals();
						}
					}
				}
			}
			$cart->setPacks($packs);
		}

		if ($cart->validate()) {

			$cart->save(false);

			$shipping = $cart->getShippingAddress();
			$person->personalInfoMapping->name = $shipping->name;
			$person->personalInfoMapping->last_name = $shipping->last_name;
			$person->personalInfoMapping->city = $shipping->city;
			$person->personalInfoMapping->country = $shipping->country;
			$person->personalInfoMapping->address = $shipping->address;
			$person->personalInfoMapping->zip = $shipping->zip;
			$person->personalInfoMapping->vat_id = $shipping->vat_id;
			$person->personalInfoMapping->phone_number_prefix = $shipping->phone_number_prefix;
			$person->personalInfoMapping->phone_number = $shipping->phone_number;

			$person->setAttribute('personal_info', $person->personalInfoMapping);
			$person->save(true, ['personal_info']);

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
		$order = Order::findOneSerialized($cartId);
		/* @var Order $order */

		if (empty($order)) {
			throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
		}

		if (!$order->isCart()) {
			throw new ConflictHttpException("This order is in an invalid state");
		}

		if (!$order->isEditable()) {
			throw new UnauthorizedHttpException("You have no access to this order");
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

		if (!$order->isCart()) {
			throw new ConflictHttpException("This order is in an invalid state");
		}

		try {

			$order->setState(Order::ORDER_STATE_PAID);
			$order->save();

			Stripe::setApiKey(Yii::$app->params['stripe_secret_key']);

			$person = $order->getPerson();

			$packs = $order->getPacks();

			$shippingAddress = $order->getShippingAddress();
			$billingAddress = $order->getBillingAddress();

			// Create a customer in stripe for the received token
			$customer = \Stripe\Customer::create([
				'email' => $person->credentials['email'],
				'business_vat_id' => $billingAddress->vat_id,
				'shipping' => [
					'address' => [
						'line1' => $shippingAddress->address,
						'city' => $shippingAddress->city,
						'country' => $shippingAddress->country,
						'postal_code' => $shippingAddress->zip,
					],
					'name' => $shippingAddress->getFullName(),
					'phone' => $shippingAddress->getPhone(),
				],
				'source' => $token,
			]);

			$charges = [];
			foreach ($packs as $pack) {
				$pack->recalculateTotals();
				$deviser = $pack->getDeviser();

				$stripeAmount = (int)(($pack->pack_price + $pack->shipping_price) * 100);

				if (empty($deviser->settingsMapping->stripeInfoMapping->access_token)) {

					// If the deviser has no a connected stripe account, we charge directly to todevise account
					$charge = \Stripe\Charge::create([
						'customer' => $customer->id,
						'currency' => 'eur',
						'amount' => $stripeAmount,
						"description" => "Order Nº " . $order->short_id . "/" . $pack->short_id,
						"metadata" => [
							"order_id" => $order->short_id,
							"pack_id" => $pack->short_id,
							"person_id" => $person->short_id,
						],
					]);

					// No fees
					$pack->pack_percentage_fee = 0;
					$pack->pack_percentage_fee_todevise = 0;
					$pack->pack_percentage_fee_vat = 0;

				} else {

					// Create a Token for the customer on the connected deviser account
					$token = \Stripe\Token::create(
						[
							"customer" => $customer->id,
							"card" => $currentPaymentInfo['card']['id'],
						],
						[
							// id of the connected account
							"stripe_account" => $deviser->settingsMapping->stripeInfoMapping->stripe_user_id,
						]
					);

					$todeviseFeePercentage = $deviser->getTodeviseFee();
					$vatOverFeePercentage = $deviser->getVatOverFee();
					$totalFeePercentage = $deviser->getSalesApplicationFee();

					// Set fees
					$pack->pack_percentage_fee = $totalFeePercentage;
					$pack->pack_percentage_fee_todevise = $todeviseFeePercentage;
					$pack->pack_percentage_fee_vat = $vatOverFeePercentage;

					$applicationFeeAmount = round($stripeAmount * $totalFeePercentage, 0);

					// Create a charge for this customer in the connected deviser account
					$charge = \Stripe\Charge::create(
						[
							'source' => $token,
							'currency' => 'eur',
							'amount' => $stripeAmount,
							"description" => "Order Nº " . $order->short_id . "/" . $pack->short_id,
							'application_fee' => $applicationFeeAmount,
							"metadata" => [
								"order_id" => $order->short_id,
								"pack_id" => $pack->short_id,
								"person_id" => $person->short_id,
							],
						],
						[
							// id of the connected account
							'stripe_account' => $deviser->settingsMapping->stripeInfoMapping->stripe_user_id,
						]
					);
				}

				// Recalculate totals
				$pack->pack_total_price = $pack->pack_price + $pack->shipping_price;
				$pack->pack_total_fee_todevise = round($pack->pack_total_price * $pack->pack_percentage_fee_todevise, 2);
				$pack->pack_total_fee_vat = round($pack->pack_total_fee_todevise * $pack->pack_percentage_fee_vat, 2);
				$pack->pack_total_fee = $pack->pack_total_fee_todevise + $pack->pack_total_fee_vat;

				$pack->charge_info = [
					'id' => $charge->id,
					'object' => $charge->object,
					'amount' => $charge->amount,
					'amount_refunded' => $charge->amount_refunded,
					'application' => $charge->application,
					'application_fee' => $charge->application_fee,
					'balance_transaction' => $charge->balance_transaction,
					'captured' => $charge->captured,
					'created' => $charge->created,
					'currency' => $charge->currency,
					'customer' => $charge->customer,
					'description' => $charge->description,
					'destination' => $charge->destination,
					'receipt_email' => $charge->receipt_email,
					'status' => $charge->status,
				];
				$pack->setState(OrderPack::PACK_STATE_PAID);

				$charges[] = $charge;
			}

			$order->setPacks($packs);

			// Save charges responses and payment_info in the order
			$order->setAttribute('payment_info', $currentPaymentInfo);
			$order->save();

			$order->composeEmailOrderPaid(true);

			Yii::$app->response->setStatusCode(200); // Created

			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);
			$order = Order::findOneSerialized($cartId);
			$order->setSubDocumentsForSerialize();

			return $order;
		} catch (\Exception $e) {
			$message = sprintf("Error in receive-token: " . $e->getMessage());
			Yii::info($message, 'Stripe');

			$order->setState(Order::ORDER_STATE_FAILED);
			$order->save();


			throw $e;
		}
	}
}