<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Loved;
use Yii;
use yii\web\NotFoundHttpException;

class LovedController extends AppPublicController
{

	public function actionView($lovedId)
	{
		Loved::setSerializeScenario(Loved::SERIALIZE_SCENARIO_OWNER);
		$loved = Loved::findOneSerialized($lovedId);
		if (empty($loved)){
			throw new NotFoundHttpException(sprintf("Loved with id %s does not exists", $lovedId));
		}

		return $loved;
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Loved::setSerializeScenario(Loved::SERIALIZE_SCENARIO_PUBLIC);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 99999);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$loveds = Loved::findSerialized([
			"id" => Yii::$app->request->get("id"),
			"person_id" => Yii::$app->request->get("person_id"),
			"product_id" => Yii::$app->request->get("product_id"),
			"limit" => $limit,
			"offset" => $offset,
		]);

		return [
			"items" => $loveds,
			"meta" => [
				"total_count" => Loved::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}
}