<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Order;
use app\models\Post;
use Yii;
use yii\web\NotFoundHttpException;

class PostController extends AppPublicController
{

	public function actionView($postId)
	{
		Post::setSerializeScenario(Order::SERIALIZE_SCENARIO_PUBLIC);
		$post = Post::findOneSerialized($postId);

		if (empty($post)) {
			throw new NotFoundHttpException();
		}

		if ($post->post_state != Post::POST_STATE_ACTIVE) {
			throw new NotFoundHttpException("The post is no active");
		}

		Yii::$app->response->setStatusCode(200); // Ok
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

		$boxs = Post::findSerialized([
			"id" => Yii::$app->request->get("id"),
			"text" => Yii::$app->request->get("q"), // search in name, description, and more
			"person_id" => Yii::$app->request->get("person_id"),
			"post_state" => Post::POST_STATE_ACTIVE,
			"only_active_persons" => true,
			"limit" => $limit,
			"offset" => $offset,
		]);

		return [
			"items" => $boxs,
			"meta" => [
				"total_count" => Post::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}
}