<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Loved;
use app\models\Timeline;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class LovedController extends AppPrivateController
{
	public function actionView($lovedId)
	{
		Loved::setSerializeScenario(Loved::SERIALIZE_SCENARIO_OWNER);
		$loved = Loved::findOneSerialized($lovedId);
		if (empty($loved)){
			throw new NotFoundHttpException(sprintf("Loved with id %s does not exists", $lovedId));
		}
		if ($loved->person_id != Yii::$app->user->identity->short_id) {
			throw new ForbiddenHttpException();
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
			"person_id" => Yii::$app->user->identity->short_id,
			"product_id" => Yii::$app->request->get("product_id"),
			"box_id" => Yii::$app->request->get("box_id"),
			"post_id" => Yii::$app->request->get("post_id"),
			"timeline_id" => Yii::$app->request->get("timeline_id"),
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

	public function actionCreate()
	{
		Loved::setSerializeScenario(Loved::SERIALIZE_SCENARIO_OWNER);
		$loved = new Loved();

		if (Yii::$app->request->post('product_id')) {
			$loved->setScenario(Loved::SCENARIO_LOVED_PRODUCT);
		} elseif (Yii::$app->request->post('box_id')) {
			$loved->setScenario(Loved::SCENARIO_LOVED_BOX);
		} elseif (Yii::$app->request->post('post_id')) {
			$loved->setScenario(Loved::SCENARIO_LOVED_POST);
		} elseif (Yii::$app->request->post('timeline_id')) {
			$loved->setScenario(Loved::SCENARIO_LOVED_TIMELINE);
		}

		$loved->person_id = Yii::$app->user->identity->short_id;
		if ($loved->load(Yii::$app->request->post(), '') && $loved->validate()) {

			$loved->save(false);

			$timeline = new Timeline();
			$timeline->person_id = $loved->person_id;
			if ($loved->product_id) {
				$timeline->target_id = $loved->product_id;
				$timeline->action_type = Timeline::ACTION_PRODUCT_LOVED;
			} elseif ($loved->box_id) {
				$timeline->target_id = $loved->box_id;
				$timeline->action_type = Timeline::ACTION_BOX_LOVED;
			} elseif ($loved->post_id) {
				$timeline->target_id = $loved->post_id;
				$timeline->action_type = Timeline::ACTION_POST_LOVED;
			} elseif ($loved->timeline_id) {
				$timeline->target_id = $loved->timeline_id;
				$timeline->action_type = Timeline::ACTION_TIMELINE_LOVED;
			}
			$timeline->date = new \MongoDate();
			$timeline->save();

			Yii::$app->response->setStatusCode(201); // Created
			return $loved;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $loved->errors];
		}
	}

	public function actionDeleteProduct($productId)
	{
		/** @var Loved $loved */
		$loveds = Loved::findSerialized([
			'product_id' => $productId,
			'person_id' => Yii::$app->user->identity->short_id,
		]);
		if (!$loveds) {
			throw new NotFoundHttpException('Loved not found');

		}
		foreach ($loveds as $loved) {
			$loved->delete();
		}
		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}

	public function actionDeleteBox($boxId)
	{
		/** @var Loved $loved */
		$loveds = Loved::findSerialized([
			'box_id' => $boxId,
			'person_id' => Yii::$app->user->identity->short_id,
		]);
		if (!$loveds) {
			throw new NotFoundHttpException('Loved not found');

		}
		foreach ($loveds as $loved) {
			$loved->delete();
		}
		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}

	public function actionDeletePost($postId)
	{
		/** @var Loved $loved */
		$loveds = Loved::findSerialized([
			'post_id' => $postId,
			'person_id' => Yii::$app->user->identity->short_id,
		]);
		if (!$loveds) {
			throw new NotFoundHttpException('Loved not found');

		}
		foreach ($loveds as $loved) {
			$loved->delete();
		}
		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}

	public function actionDeleteTimeline($timelineId)
	{
		/** @var Loved $loved */
		$loveds = Loved::findSerialized([
			'timeline_id' => $timelineId,
			'person_id' => Yii::$app->user->identity->short_id,
		]);
		if (!$loveds) {
			throw new NotFoundHttpException('Loved not found');

		}
		foreach ($loveds as $loved) {
			$loved->delete();
		}
		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}
}