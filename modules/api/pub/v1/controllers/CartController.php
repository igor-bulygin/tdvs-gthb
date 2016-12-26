<?php

namespace app\modules\api\pub\v1\controllers;

use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;

class CartController extends AppPublicController
{

	public function actionIndex()
	{
		return $this->getCart();
	}

	public function actionCreate()
	{
		try {
			$cart = $this->convertToObject(Yii::$app->request->post());
			Yii::$app->session['cart'] = $cart;
			Yii::$app->response->setStatusCode(201); // Created
			return $cart;
		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}


	protected function getCart() {
		$cart = Yii::$app->session['cart'];
		return $cart;
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