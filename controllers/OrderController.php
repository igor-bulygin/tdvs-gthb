<?php

namespace app\controllers;

use app\helpers\CController;
use app\models\Order;
use yii\base\Exception;
use yii\web\NotFoundHttpException;

class OrderController extends CController
{
	/**
	 * @inheritdoc
	 */
	public function beforeAction($action)
	{
		if ($action->id == 'receive-payment') {
			$this->enableCsrfValidation = false;
		}

		return parent::beforeAction($action);
	}

	public function actionSuccess($order_id)
	{
		$order = Order::findOneSerialized($order_id);
		/* @var Order $order */
		if (empty($order)) {
			throw new NotFoundHttpException();
		}

		if ($order->order_state != Order::ORDER_STATE_PAID) {
			throw new Exception("This order is in an invalid state");
		}

		$order->order_state = Order::ORDER_STATE_CART;
		$order->save();

		$this->layout = '/desktop/public-2.php';

		return $this->render("success", [
		]);
	}
}