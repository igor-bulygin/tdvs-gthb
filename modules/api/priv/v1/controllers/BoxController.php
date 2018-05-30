<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Box;
use app\models\BoxProduct;
use app\models\Timeline;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class BoxController extends AppPrivateController
{
	public function actionView($boxId)
	{
		Box::setSerializeScenario(Box::SERIALIZE_SCENARIO_OWNER);
		$box = Box::findOneSerialized($boxId);
		if (empty($box)){
			throw new NotFoundHttpException(sprintf("Box with id %s does not exists", $boxId));
		}
		if ($box->person_id != Yii::$app->user->identity->short_id) {
			throw new ForbiddenHttpException();
		}

		return $box;
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Box::setSerializeScenario(Box::SERIALIZE_SCENARIO_PUBLIC);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 99999);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$boxs = Box::findSerialized([
			"id" => Yii::$app->request->get("id"),
			"person_id" => Yii::$app->user->identity->short_id,
			"product_id" => Yii::$app->request->get("product_id"),
			"ignore_empty_boxes" => Yii::$app->request->get("ignore_empty_boxes", false),
			"limit" => $limit,
			"offset" => $offset,
		]);

		return [
			"items" => $boxs,
			"meta" => [
				"total_count" => Box::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}

	public function actionCreate()
	{
		Box::setSerializeScenario(Box::SERIALIZE_SCENARIO_OWNER);
		$box = new Box();

		$box->setScenario(Box::SCENARIO_BOX_CREATE);
		$box->person_id = Yii::$app->user->identity->short_id;
		if ($box->load(Yii::$app->request->post(), '') && $box->validate()) {

			$box->save(false);

			Yii::$app->response->setStatusCode(201); // Created
			return $box;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $box->errors];
		}
	}

	public function actionUpdate($boxId)
	{
		Box::setSerializeScenario(Box::SERIALIZE_SCENARIO_OWNER);

		$box = Box::findOneSerialized($boxId); /* @var Box $box */

		if (empty($box)) {
			throw new NotFoundHttpException(sprintf("Box with id %s does not exists", $boxId));
		}
		if (!$box->isEditable()) {
			throw new ForbiddenHttpException();
		}
		$box->setScenario(Box::SCENARIO_BOX_UPDATE);
		if ($box->load(Yii::$app->request->post(), '') && $box->validate(array_keys(Yii::$app->request->post()))) {

			$box->save(false);

			$timeline = new Timeline();
			$timeline->person_id = $box->person_id;
			$timeline->target_id = $box->short_id;
			$timeline->action_type = Timeline::ACTION_BOX_UPDATED;
			$timeline->date = new \MongoDate();
			$timeline->save();

			Yii::$app->response->setStatusCode(201); // Created
			return $box;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $box->errors];
		}
	}

	public function actionDelete($boxId)
	{
		$box = Box::findOneSerialized($boxId); /* @var Box $box */

		if (empty($box)) {
			throw new NotFoundHttpException(sprintf("Box with id %s does not exists", $boxId));
		}
		if (!$box->isEditable()) {
			throw new ForbiddenHttpException();
		}

		$box->delete();

		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}

	public function actionAddProduct($boxId)
	{
		Box::setSerializeScenario(Box::SERIALIZE_SCENARIO_PUBLIC);
		$box = Box::findOneSerialized($boxId);
		/* @var Box $box */

		if (empty($box)) {
			throw new NotFoundHttpException(sprintf("Box with id %s does not exists", $boxId));
		}
		if (!$box->isEditable()) {
			throw new ForbiddenHttpException();
		}
		$boxProduct = new BoxProduct();
		$boxProduct->setParentObject($box);

		if ($boxProduct->load(Yii::$app->request->post(), '') && $boxProduct->validate()) {

			$box->addProduct($boxProduct);

			$timeline = new Timeline();
			$timeline->person_id = $box->person_id;
			$timeline->target_id = $box->short_id;
			if (count($box->productsMapping) == 1) {
				$timeline->action_type = Timeline::ACTION_BOX_CREATED;
			} else {
				$timeline->action_type = Timeline::ACTION_BOX_UPDATED;
			}
			$timeline->date = new \MongoDate();
			$timeline->save();

			Yii::$app->response->setStatusCode(201); // Created
			return $box;

		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $boxProduct->errors];
		}
	}


	public function actionDeleteProduct($boxId, $productId)
	{
		Box::setSerializeScenario(Box::SERIALIZE_SCENARIO_PUBLIC);
		$box = Box::findOneSerialized($boxId); /* @var Box $box */

		if (empty($box)) {
			throw new NotFoundHttpException(sprintf("Box with id %s does not exists", $boxId));
		}
		if (!$box->isEditable()) {
			throw new ForbiddenHttpException();
		}

		$box->deleteProduct($productId);

		Yii::$app->response->setStatusCode(200); // Ok

		return $box;
	}
}