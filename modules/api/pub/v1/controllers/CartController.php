<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Order;
use app\models\OrderClientInfo;
use app\models\OrderProduct;
use Stripe\Stripe;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;

class CartController extends AppPublicController
{

	public function actionCreateCart()
	{
		try {
			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
			$cart = new Order();
			if (!Yii::$app->user->isGuest) {
				$cart->client_id = Yii::$app->user->identity->short_id;
			}
			$cart->subtotal = 0;
			$cart->save();

			Yii::$app->response->setStatusCode(201); // Created
			return $cart;

		} catch (HttpException $e) {
			throw $e;
		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}

	public function actionView($cartId)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
		$order = Order::findOneSerialized($cartId);

		if ($order && $order->order_state == Order::ORDER_STATE_CART) {
			Yii::$app->response->setStatusCode(200); // Ok
			return $order;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return [];
		}
	}

	public function actionAddProduct($cartId)
	{
		try {

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

		} catch (HttpException $e) {
			throw $e;
		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}


	public function actionUpdateProduct($cartId, $priceStockId)
	{
		try {

			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
			$cart = Order::findOneSerialized($cartId);
			/* @var Order $cart */
			if (empty($cart)) {
				throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
			}
			$product = $cart->getPriceStockItem($priceStockId);
			if (empty($product)) {
				throw new NotFoundHttpException(sprintf("Price&Stock item with id %s does not exists", $priceStockId));
			}

			if ($product->load(Yii::$app->request->post(), '') && $product->validate()) {

				$cart->updateProduct($product);
				$cart->save();

				Yii::$app->response->setStatusCode(200); // Ok
				return $cart;
			} else {
				Yii::$app->response->setStatusCode(400); // Bad Request
				return ["errors" => $product->errors];
			}

		} catch (HttpException $e) {
			throw $e;
		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}


	public function actionDeleteProduct($cartId, $priceStockId)
	{
		try {
			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
			$cart = Order::findOneSerialized($cartId);
			/* @var Order $cart */
			if (empty($cart)) {
				throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
			}
			$product = $cart->getPriceStockItem($priceStockId);
			if (empty($product)) {
				throw new NotFoundHttpException(sprintf("Price&Stock item with id %s does not exists", $priceStockId));
			}

			$cart->deleteProduct($product);
			$cart->save();

			Yii::$app->response->setStatusCode(200); // Ok

			return $cart;

		} catch (HttpException $e) {
			throw $e;
		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}


	public function actionClientInfo($cartId)
	{
		try {

			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
			$cart = Order::findOneSerialized($cartId);
			/* @var Order $cart */

			if (empty($cart)) {
				throw new NotFoundHttpException(sprintf("Cart with id %s does not exists", $cartId));
			}

			$clientInfo = new OrderClientInfo();
			$clientInfo->setParentObject($cart);

			if ($clientInfo->load(Yii::$app->request->post(), '') && $clientInfo->validate()) {

				$cart->clientInfoMapping = $clientInfo;
				$cart->save();

				Yii::$app->response->setStatusCode(200); // Created
				return $cart;
			} else {
				Yii::$app->response->setStatusCode(400); // Bad Request
				return ["errors" => $clientInfo->errors];
			}

		} catch (HttpException $e) {
			throw $e;
		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}


	public function actionReceiveToken($cartId)
	{
		try {

			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
			$order = Order::findOneSerialized($cartId);
			/* @var Order $order */

			if (empty($order)) {
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

			$order->setAttribute('payment_info', $currentPaymentInfo);
			if ($order->save()) {

				if ($order->order_state != Order::ORDER_STATE_CART) {
					throw new Exception("This order is in an invalid state");
				}

				Stripe::setApiKey('sk_test_eLdJxVmKSGQxGPhX2bqpoRk4');
				$customer = \Stripe\Customer::create([
					'email' => $order->clientInfoMapping->email,
					'source' => $currentPaymentInfo['id'],
				]);

				$charge = \Stripe\Charge::create([
					'customer' => $customer->id,
					'currency' => 'eur',
					'amount' => $order->subtotal*100,
					"description" => "Order Nº " . $order->short_id,
					"metadata" => [
						"order_id" => $order->short_id,
						"order" => json_encode($order),
					],
				]);

				//TODO: set paid status
//				$order->order_state = Order::ORDER_STATE_PAID;
//				$order->save();

				//TODO: send email to customer

				Yii::$app->response->setStatusCode(200); // Created
				return $order;
			} else {
				Yii::$app->response->setStatusCode(400); // Bad Request
				return ["errors" => $order->errors];
			}

		} catch (HttpException $e) {
			throw $e;
		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}
}