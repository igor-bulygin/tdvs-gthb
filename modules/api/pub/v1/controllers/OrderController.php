<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Order;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class OrderController extends AppPublicController
{

	public function actionView($orderId)
	{
		try {
			Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
			$order = Order::findOneSerialized($orderId);

			if (empty($order)) {
				throw new NotFoundHttpException();
			}

			if ($order->order_state == Order::ORDER_STATE_CART) {
				throw new NotFoundHttpException();
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

		} catch (HttpException $e) {
			throw $e;
		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}
}