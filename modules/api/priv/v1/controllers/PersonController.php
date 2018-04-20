<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Order;
use app\models\OrderPack;
use app\models\Person;
use Yii;
use yii\base\Exception;
use yii\web\BadRequestHttpException;
use yii\web\ConflictHttpException;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;

class PersonController extends AppPrivateController
{

	public function actionView($personId)
	{
		Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);

		/** @var Person $person */
		$person = Person::findOneSerialized($personId);
		if (empty($person)) {
			throw new NotFoundHttpException('Person not found');
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		return $person;
	}

	public function actionUpdate($personId)
	{
		/** @var Person $person */
		$person = Person::findOne(["short_id" => $personId]);
		if (empty($person)) {
			throw new NotFoundHttpException('Person not found');
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$newAccountState = Yii::$app->request->post('account_state', $person->account_state);
		$this->checkDeviserAccountState($person, $newAccountState); // check for allowed new account state only

		// only validate received fields (only if we are not changing the state)
		$validateFields = $person->account_state == $newAccountState ? array_keys(Yii::$app->request->post()) : null;

		$person->setScenario($this->getScenarioFromRequest($person));

		if ($person->load(Yii::$app->request->post(), '') && $person->validate($validateFields)) {

			$person->save(false);

			Person::setSerializeScenario(Person::SERIALIZE_SCENARIO_OWNER);
			return $person;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return [
				"errors" => $person->errors,
			];
		}

	}

	public function actionUpdatePassword($personId)
	{
		/** @var Person $person */
		$person = Person::findOne(["short_id" => $personId]);
		if (empty($person)) {
			throw new NotFoundHttpException('Person not found');
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		$oldPassword = Yii::$app->request->post('oldpassword');
		$newPassword = Yii::$app->request->post('newpassword');

		if (empty($oldPassword) || !empty($person->credentials['password']) && !$person->validatePassword($oldPassword)) {
			throw new BadRequestHttpException("Invalid old password");
		}
		if (empty($newPassword)) {
			throw new BadRequestHttpException("Invalid new password");
		}
		$person->setPassword($newPassword);
		$person->save();

		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}

	public function actionOrder($personId, $orderId)
	{
		/** @var Person $person */
		$person = Person::findOne(["short_id" => $personId]);
		if (empty($person)) {
			throw new NotFoundHttpException('Person not found');
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		// show only fields needed in this scenario
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);

		$order = Order::findOneSerialized($orderId);
		if (empty($order)) {
			throw new NotFoundHttpException('Order not found');
		}

		if ($order->person_id != $person->short_id) {
			throw new UnauthorizedHttpException();
		}

		if (!$order->isOrder()) {
			throw new ConflictHttpException("This order has an invalid state");
		}

		$order->setSubDocumentsForSerialize();

		return $order;
	}

	public function actionOrders($personId)
	{
		/** @var Person $person */
		$person = Person::findOne(["short_id" => $personId]);
		if (empty($person)) {
			throw new NotFoundHttpException('Person not found');
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		// show only fields needed in this scenario
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_CLIENT_ORDER);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 99999);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));
		$order_col = Yii::$app->request->get('order_col', 'created_at');
		$order_dir = Yii::$app->request->get('order_dir', 'asc');

		$orders = Order::findSerialized([
			"person_id" => $person->id,
			"order_state" => Order::ORDER_STATE_PAID,
			"pack_state" => Yii::$app->request->get('pack_state'),
			"limit" => $limit,
			"offset" => $offset,
			"order_col" => $order_col,
			"order_dir" => $order_dir,
		]);

		foreach ($orders as $order) {
			$order->setSubDocumentsForSerialize();
		}

		return [
			"items" => $orders,
			"meta" => [
				"total_count" => Order::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}

	public function actionPacks($personId)
	{
		/** @var Person $person */
		$person = Person::findOne(["short_id" => $personId]);
		if (empty($person)) {
			throw new NotFoundHttpException('Person not found');
		}

		if (!$person->isDeviser()) {
			throw new Exception("Invalid person type");
		}

		if (!$person->isDeviserEditable()) {
			throw new UnauthorizedHttpException();
		}

		// show only fields needed in this scenario
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_DEVISER_PACK);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 99999);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));
		$order_col = Yii::$app->request->get('order_col', 'created_at');
		$order_dir = Yii::$app->request->get('order_dir', 'asc');

		$orders = Order::findSerialized([
			"deviser_id" => $person->id,
			"only_matching_packs" => true,
			"order_state" => Order::ORDER_STATE_PAID,
			"pack_state" => Yii::$app->request->get('pack_state'),
			"limit" => $limit,
			"offset" => $offset,
			"order_col" => $order_col,
			"order_dir" => $order_dir,
		]);

		foreach ($orders as $order) {
			$order->setSubDocumentsForSerialize();
		}

		return [
			"items" => $orders,
			"meta" => [
				"total_count" => Order::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}

	public function actionPackAware($personId, $packId)
	{
		/** @var Person $person */
		$person = Person::findOne(["short_id" => $personId]);
		if (empty($person)) {
			throw new NotFoundHttpException('Person not found');
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		// show only fields needed in this scenario
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_DEVISER_PACK);
		$orders = Order::findSerialized(
			[
				'pack_id' => $packId,
				'deviser_id' => $person->short_id,
			]
		);
		if (count($orders) != 1) {
			throw new NotFoundHttpException(sprintf('Order for pack_id %s not found', $packId));
		}

		// We can only get one order by packId...
		/* @var Order $order */
		$order = $orders[0];

		if (!$order->isOrder()) {
			throw new ConflictHttpException("This order has an invalid state");
		}

		// Change values in the pack
		$pack = $order->getPack($packId);
		if (!$pack) {
			throw new NotFoundHttpException(sprintf('Pack with id %s not found', $packId));
		}
		$pack->setState(OrderPack::PACK_STATE_AWARE);

		// Set pack in the order and save
		$order->setPack($packId, $pack);

		// We get the order another time, but with "only_matching_packs" to prevent other packs to apear in the response
		$orders = Order::findSerialized(
			[
				'pack_id' => $packId,
				'deviser_id' => $person->short_id,
				'only_matching_packs' => true,
			]
		);
		if (count($orders) != 1) {
			throw new NotFoundHttpException(sprintf('Order for pack_id %s not found', $packId));
		}

		// We can only get one order by packId...
		/* @var Order $order */
		$order = $orders[0];
		$order->setSubDocumentsForSerialize();

		return $order;
	}

	public function actionPackShipped($personId, $packId)
	{
		/** @var Person $person */
		$person = Person::findOne(["short_id" => $personId]);
		if (empty($person)) {
			throw new NotFoundHttpException('Person not found');
		}

		if (!$person->isPersonEditable()) {
			throw new UnauthorizedHttpException();
		}

		// show only fields needed in this scenario
		Order::setSerializeScenario(Order::SERIALIZE_SCENARIO_DEVISER_PACK);
		$orders = Order::findSerialized(
			[
				'pack_id' => $packId,
				'deviser_id' => $person->short_id,
			]
		);
		if (count($orders) != 1) {
			throw new NotFoundHttpException(sprintf('Order for pack_id %s not found', $packId));
		}

		// We can only get one order by packId...
		/* @var Order $order */
		$order = $orders[0];

		if (!$order->isOrder()) {
			throw new ConflictHttpException("This order has an invalid state");
		}

		// Change values in the pack
		$pack = $order->getPack($packId);
		if (!$pack) {
			throw new NotFoundHttpException(sprintf('Pack with id %s not found', $packId));
		}
		$pack->setPackShippingInfo(Yii::$app->request->post());

		$invoiceUrl = Yii::$app->request->post('invoice_url');
		if ($invoiceUrl) {
			$pack->setInvoiceInfo($invoiceUrl);
		}

		$pack->setState(OrderPack::PACK_STATE_SHIPPED);

		// Set pack in the order and save
		$order->setPack($packId, $pack);

		// We get the order another time, but with "only_matching_packs" to prevent other packs to apear in the response
		$orders = Order::findSerialized(
			[
				'pack_id' => $packId,
				'deviser_id' => $person->short_id,
				'only_matching_packs' => true,
			]
		);
		if (count($orders) != 1) {
			throw new NotFoundHttpException(sprintf('Order for pack_id %s not found', $packId));
		}

		// We can only get one order by packId...
		/* @var Order $order */
		$order = $orders[0];
		$order->setSubDocumentsForSerialize();

		return $order;
	}

	/**
	 * Get validation scenario from request param
	 *
	 * @param Person $person
	 * @return string
	 * @throws BadRequestHttpException
	 */
	private function getScenarioFromRequest(Person $person)
	{
		$account_state = Yii::$app->request->post('account_state', $person->account_state);

		// can't change from "active" to "draft"
		if ($person->account_state == Person::ACCOUNT_STATE_ACTIVE || $account_state == Person::ACCOUNT_STATE_ACTIVE) {
			// it is updating a active profile (or a profile that want to be active)
			if ($person->isDeviser()) {
				$scenario = Person::SCENARIO_DEVISER_UPDATE_PROFILE;
			} elseif ($person->isInfluencer()) {
				$scenario = Person::SCENARIO_INFLUENCER_UPDATE_PROFILE;
			} elseif ($person->isClient() || $person->isAdmin()) {
				$scenario = Person::SCENARIO_CLIENT_UPDATE;
			} else{
				throw new Exception("Unknown person type");
			}
		} else {
			// it is updating a draft profile
			if ($person->isDeviser()) {
				$scenario = Person::SCENARIO_DEVISER_UPDATE_DRAFT;
			} elseif ($person->isInfluencer()) {
				$scenario = Person::SCENARIO_INFLUENCER_UPDATE_DRAFT;
			} elseif ($person->isClient() || $person->isAdmin()) {
				$scenario = Person::SCENARIO_CLIENT_UPDATE;
			} else {
				throw new Exception("Unknown person type");
			}
		}

		return $scenario;
	}

	/**
	 * Logic for assign new person account state.
	 * Only allow change state to "active", otherwise, raise an exception
	 *
	 * @param Person $person
	 * @param $accountState
	 * @throws BadRequestHttpException
	 */
	private function checkDeviserAccountState(Person $person, $accountState)
	{
		if (!empty($accountState)) {
			// allowed new account state depends on current account state
			switch ($person->account_state) {
				case Person::ACCOUNT_STATE_DRAFT:
					if (!in_array($accountState, [Person::ACCOUNT_STATE_DRAFT, Person::ACCOUNT_STATE_ACTIVE])) {
						throw new BadRequestHttpException('Invalid account state');
					}
					break;
				case Person::ACCOUNT_STATE_ACTIVE:
					if (!in_array($accountState, [Person::ACCOUNT_STATE_ACTIVE])) {
						throw new BadRequestHttpException('Invalid account state');
					}
					break;
			}
		}
	}
}

