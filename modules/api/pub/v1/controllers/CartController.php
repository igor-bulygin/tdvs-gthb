<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Order;
use app\models\OrderProduct;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;

class CartController extends AppPublicController
{

	public function actionView($id)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
		$cart = Order::findOneSerialized($id);

		if ($cart) {
			Yii::$app->response->setStatusCode(200); // Ok
			return $cart;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return [];
		}
	}

	public function actionCreateProduct()
	{
		try {

			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
			$product = new OrderProduct();
			$cart = new Order();
			$cart->client_id = null;
			$cart->subtotal = 0;
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


	public function actionUpdateProduct($id)
	{
		try {
			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);

			$cart = Order::findOneSerialized($id);

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