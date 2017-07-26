<?php

namespace app\controllers;

use app\helpers\CController;
use app\models\Order;
use app\models\Person;
use yii\base\Exception;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class OrderController extends CController
{
	public function actionSuccess($order_id)
	{
		$order = Order::findOneSerialized($order_id);
		/* @var Order $order */
		if (empty($order)) {
			throw new NotFoundHttpException();
		}

		if (!$order->isOrder()) {
			throw new Exception("This order is in an invalid state");
		}

		if (!$order->isEditable()) {
			throw new UnauthorizedHttpException("You have no access to this order");
		}

		$this->layout = '/desktop/public-2.php';

		return $this->render("success", [
			'person' => $order->getPerson(),
		]);
	}
}