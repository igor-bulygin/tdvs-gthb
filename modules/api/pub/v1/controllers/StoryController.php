<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Order;
use app\models\Story;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class StoryController extends AppPublicController
{

	public function actionView($storyId)
	{
		Story::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
		$story = Story::findOneSerialized($storyId);

		if (empty($story)) {
			throw new NotFoundHttpException();
		}

		if ($story->story_state != Story::STORY_STATE_ACTIVE) {
			throw new BadRequestHttpException();
		}

		Yii::$app->response->setStatusCode(200); // Ok
		return $story;
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Story::setSerializeScenario(Story::SERIALIZE_SCENARIO_PUBLIC);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 99999);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$boxs = Story::findSerialized([
			"id" => Yii::$app->request->get("id"),
			"text" => Yii::$app->request->get("q"), // search in name, description, and more
			"person_id" => Yii::$app->request->get("person_id"),
			"categories" => Yii::$app->request->get("categories"),
			"countries" => Yii::$app->request->get("countries"),
			"story_state" => Story::STORY_STATE_ACTIVE,
			"only_active_persons" => true,
			"limit" => $limit,
			"offset" => $offset,
		]);

		return [
			"items" => $boxs,
			"meta" => [
				"total_count" => Story::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}
}