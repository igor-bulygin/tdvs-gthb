<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Story;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class StoryController extends AppPrivateController
{
	public function actionView($storyId)
	{
		Story::setSerializeScenario(Story::SERIALIZE_SCENARIO_OWNER);
		$story = Story::findOneSerialized($storyId);
		if (empty($story)){
			throw new NotFoundHttpException(sprintf("Story with id %s does not exists", $storyId));
		}
		if ($story->person_id != Yii::$app->user->identity->short_id) {
			throw new ForbiddenHttpException();
		}

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

		$stories = Story::findSerialized([
			"id" => Yii::$app->request->get("id"),
			"person_id" => Yii::$app->user->identity->short_id,
			"story_state" => Yii::$app->request->get("story_state"),
			"limit" => $limit,
			"offset" => $offset,
		]);

		return [
			"items" => $stories,
			"meta" => [
				"total_count" => Story::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}

	public function actionCreate()
	{
		Story::setSerializeScenario(Story::SERIALIZE_SCENARIO_OWNER);
		$story = new Story();

		$story->setScenario(Story::SCENARIO_STORY_CREATE_DRAFT);
		$story->person_id = Yii::$app->user->identity->short_id;
		if ($story->load(Yii::$app->request->post(), '') && $story->validate()) {

			$story->save();

			Yii::$app->response->setStatusCode(201); // Created
			return $story;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $story->errors];
		}
	}

	public function actionUpdate($storyId)
	{
		Story::setSerializeScenario(Story::SERIALIZE_SCENARIO_OWNER);

		$story = Story::findOneSerialized($storyId); /* @var Story $story */

		if (empty($story)) {
			throw new NotFoundHttpException(sprintf("Story with id %s does not exists", $storyId));
		}
		if (!$story->isEditable()) {
			throw new ForbiddenHttpException();
		}
		$story->setScenario($this->getScenarioFromRequest($story)); // safe and required attributes are related with scenario

		if ($story->load(Yii::$app->request->post(), '') && $story->validate()) {

			$story->save();

			Yii::$app->response->setStatusCode(201); // Created
			return $story;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $story->errors];
		}
	}

	public function actionDelete($storyId)
	{
		$story = Story::findOneSerialized($storyId); /* @var Story $story */

		if (empty($story)) {
			throw new NotFoundHttpException(sprintf("Story with id %s does not exists", $storyId));
		}
		if (!$story->isEditable()) {
			throw new ForbiddenHttpException();
		}

		$story->delete();

		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}

	/**
	 * Get validation scenario from request param
	 *
	 * @param Story $story
	 * @return string
	 */
	private function getScenarioFromRequest(Story $story)
	{
		$story_state = Yii::$app->request->post('story_state', $story->story_state);

		// can't change from "active" to "draft"
		if ($story->story_state == Story::STORY_STATE_ACTIVE || $story_state == Story::STORY_STATE_ACTIVE) {
			// it is updating a active story
			$scenario = Story::SCENARIO_STORY_UPDATE_ACTIVE;
		} else {
			// it is updating a draft
			$scenario = Story::SCENARIO_STORY_UPDATE_DRAFT;
		}

		return $scenario;
	}
}