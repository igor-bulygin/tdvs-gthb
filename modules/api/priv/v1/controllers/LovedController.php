<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Loved;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\UnauthorizedHttpException;

class LovedController extends AppPrivateController
{
//
//	public function actionView($id)
//	{
//		Loved::setSerializeScenario(Loved::SERIALIZE_SCENARIO_OWNER);
//		$loved = Loved::findOneSerialized($id);
//
//		return $loved;
//	}
//
//	public function actionIndex()
//	{
//		// show only fields needed in this scenario
//		Loved::setSerializeScenario(Loved::SERIALIZE_SCENARIO_PUBLIC);
//
//		// set pagination values
//		$limit = Yii::$app->request->get('limit', 20);
//		$limit = ($limit < 1) ? 1 : $limit;
//		// not allow more than 100 loveds for request
////	    $limit = ($limit > 100) ? 100 : $limit;
//		$page = Yii::$app->request->get('page', 1);
//		$page = ($page < 1) ? 1 : $page;
//		$offset = ($limit * ($page - 1));
//
//		$loveds = Loved::findSerialized([
//			"id" => Yii::$app->request->get("id"),
//			"person_id" => Yii::$app->request->get("person_id"),
//			"product_id" => Yii::$app->request->get("product_id"),
//			"limit" => $limit,
//			"offset" => $offset,
//		]);
//
//		return [
//			"items" => $loveds,
//			"meta" => [
//				"total_count" => Loved::$countItemsFound,
//				"current_page" => $page,
//				"per_page" => $limit,
//			]
//		];
//	}

	public function actionCreate()
	{
		Loved::setSerializeScenario(Loved::SERIALIZE_SCENARIO_OWNER);
		$loved = new Loved();

		if (Yii::$app->request->post('product_id')) {
			$loved->setScenario(Loved::SCENARIO_LOVED_PRODUCT);
		} elseif (Yii::$app->request->post('box_id')) {
			$loved->setScenario(Loved::SCENARIO_LOVED_BOX);
		}
		$loved->person_id = Yii::$app->user->identity->short_id;
		if ($loved->load(Yii::$app->request->post(), '') && $loved->validate()) {

			$loved->save();

			Yii::$app->response->setStatusCode(201); // Created
			return $loved;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $loved->errors];
		}
	}

	public function actionDelete($id)
	{
		/** @var Loved $loved */
		$loved = Loved::findOneSerialized($id);
		if (!$loved) {
			throw new BadRequestHttpException('Loved not found');
		}
		if (!$loved->isEditable()) {
			throw new UnauthorizedHttpException();
		}
		$loved->delete();
		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}
}