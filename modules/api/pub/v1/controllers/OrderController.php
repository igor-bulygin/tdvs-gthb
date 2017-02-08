<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Order;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class OrderController extends AppPublicController
{

	public function actionView($orderId)
	{
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
		$order = Order::findOneSerialized($orderId);

		if (empty($order)) {
			throw new NotFoundHttpException();
		}

		if ($order->order_state == Order::ORDER_STATE_CART) {
			throw new BadRequestHttpException();
		}

		if (!Yii::$app->user->isGuest) {
			if ($order->client_id != Yii::$app->user->identity->short_id) {
				throw new UnauthorizedHttpException();
			}
		} else {
			if (!empty($order->client_id)) {
				throw new UnauthorizedHttpException();
			}
		}
		Yii::$app->response->setStatusCode(200); // Ok
		return $order;
	}
}