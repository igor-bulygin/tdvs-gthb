<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Order;
use app\models\OrderProduct;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;

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

		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}

	public function actionView($cartId)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
		$cart = Order::findOneSerialized($cartId);

		if ($cart) {
			Yii::$app->response->setStatusCode(200); // Ok
			return $cart;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return [];
		}
	}

	public function actionAddProduct($cartId)
	{
		try {

			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
			$cart = Order::findOneSerialized($cartId); /* @var Order $cart */

			if (empty($cart)) {
				throw new Exception(sprintf("Cart with id %s does not exists", $cartId));
			}
			$product = new OrderProduct();
			$product->setModel($cart);

			if ($product->load(Yii::$app->request->post(), '') && $product->validate()) {

				$cart->addProduct($product);
				$cart->save();

				Yii::$app->response->setStatusCode(201); // Created
				return $cart;
			} else {
				Yii::$app->response->setStatusCode(400); // Bad Request
				return ["errors" => $product->errors];
			}

		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}


	public function actionUpdateProduct($cartId, $priceStockId)
	{
		try {

			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
			$cart = Order::findOneSerialized($cartId); /* @var Order $cart */
			if (empty($cart)) {
				throw new Exception(sprintf("Cart item with id %s does not exists", $cartId));
			}
			$product = $cart->getPriceStockItem($priceStockId);
			if (empty($product)) {
				throw new Exception(sprintf("Price&Stock item with id %s does not exists", $priceStockId));
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

		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}


	public function actionDeleteProduct($cartId, $priceStockId)
	{
		try {
			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
			$cart = Order::findOneSerialized($cartId); /* @var Order $cart */
			if (empty($cart)) {
				throw new Exception(sprintf("Cart item with id %s does not exists", $cartId));
			}
			$product = $cart->getPriceStockItem($priceStockId);
			if (empty($product)) {
				throw new Exception(sprintf("Price&Stock item with id %s does not exists", $priceStockId));
			}

			$cart->deleteProduct($product);
			$cart->save();

			Yii::$app->response->setStatusCode(200); // Ok

			return $cart;
		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}

	protected function convertToObject($array) {
		$object = new \stdClass();
		foreach ($array as $key => $value) {
			if (is_array($value)) {
				$value = $this->convertToObject($value);
			}
			$object->$key = $value;
		}
		return $object;
	}
}