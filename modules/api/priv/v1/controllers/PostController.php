<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Post;
use Yii;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;

class PostController extends AppPrivateController
{
	public function actionView($postId)
	{
		Post::setSerializeScenario(Post::SERIALIZE_SCENARIO_OWNER);
		$post = Post::findOneSerialized($postId);
		if (empty($post)){
			throw new NotFoundHttpException(sprintf("Post with id %s does not exists", $postId));
		}
		if ($post->person_id != Yii::$app->user->identity->short_id) {
			throw new ForbiddenHttpException();
		}

		return $post;
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Post::setSerializeScenario(Post::SERIALIZE_SCENARIO_PUBLIC);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 99999);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$posts = Post::findSerialized([
			"id" => Yii::$app->request->get("id"),
			"person_id" => Yii::$app->user->identity->short_id,
			"post_state" => Yii::$app->request->get("post_state"),
			"limit" => $limit,
			"offset" => $offset,
		]);

		return [
			"items" => $posts,
			"meta" => [
				"total_count" => Post::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}

	public function actionCreate()
	{
		Post::setSerializeScenario(Post::SERIALIZE_SCENARIO_OWNER);
		$post = new Post();
		$post->person_id = Yii::$app->user->identity->short_id;

		$post->setScenario($this->getScenarioFromRequest($post)); // safe and required attributes are related with scenario

		if ($post->load(Yii::$app->request->post(), '') && $post->validate()) {

			$post->save(false);

			Yii::$app->response->setStatusCode(201); // Created
			return $post;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $post->errors];
		}
	}

	public function actionUpdate($postId)
	{
		Post::setSerializeScenario(Post::SERIALIZE_SCENARIO_OWNER);

		$post = Post::findOneSerialized($postId); /* @var Post $post */

		if (empty($post)) {
			throw new NotFoundHttpException(sprintf("Post with id %s does not exists", $postId));
		}
		if (!$post->isEditable()) {
			throw new ForbiddenHttpException();
		}
		$post->setScenario($this->getScenarioFromRequest($post)); // safe and required attributes are related with scenario

		if ($post->load(Yii::$app->request->post(), '') && $post->validate(array_keys(Yii::$app->request->post()))) {

			$post->save(false);

			Yii::$app->response->setStatusCode(201); // Created
			return $post;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $post->errors];
		}
	}

	public function actionDelete($postId)
	{
		$post = Post::findOneSerialized($postId); /* @var Post $post */

		if (empty($post)) {
			throw new NotFoundHttpException(sprintf("Post with id %s does not exists", $postId));
		}
		if (!$post->isEditable()) {
			throw new ForbiddenHttpException();
		}

		$post->delete();

		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}

	/**
	 * Get validation scenario from request param
	 *
	 * @param Post $post
	 * @return string
	 */
	private function getScenarioFromRequest(Post $post)
	{
		$post_state = Yii::$app->request->post('post_state', $post->post_state);

		// can't change from "active" to "draft"
		if ($post->post_state == Post::POST_STATE_ACTIVE || $post_state == Post::POST_STATE_ACTIVE) {
			// it is updating a active post
			$scenario = Post::SCENARIO_POST_UPDATE_ACTIVE;
		} else {
			// it is updating a draft
			$scenario = Post::SCENARIO_POST_UPDATE_DRAFT;
		}

		return $scenario;
	}
}