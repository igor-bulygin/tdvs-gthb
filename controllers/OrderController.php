<?php

namespace app\controllers;

use app\helpers\CController;
use app\models\Order;
use app\models\Person;
use yii\web\ConflictHttpException;
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
			throw new ConflictHttpException(	"This order is in an invalid state");
		}

		if (!$order->isEditable()) {
			throw new UnauthorizedHttpException("You have no access to this order");
		}
		
		Person::SetSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
		$person = Person::findOneSerialized($order->person_id);

		$this->layout = '/desktop/public-2.php';

		return $this->render("success", [
			'person' => $person,
			'order_id' => $order_id
		]);
	}
}