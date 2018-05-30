<?php

namespace app\modules\api\priv\v1\controllers;

use app\helpers\Utils;
use app\models\Box;
use app\models\Person;
use app\models\Post;
use app\models\Product;
use Yii;

class TimelineController extends AppPrivateController
{
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

		/* @var Person $connected */
		$connected = Yii::$app->user->identity;

		$items = [];

		// Boxes
		$boxes = Box::findSerialized([
			'ignore_empty_boxes' => true,
			'only_active_persons' => true,
			'limit' => 10,
		]);

		foreach ($boxes as $box) {
			$items[] = [
				'id' => Utils::shortID(),
				'person' => $box->getPersonPreview(),
				'action_type' => 'box_created',
				'action_name' => 'Created a new box',
				'title' => $box->name,
				'description' => $box->description,
				'photo' => $box->getMainPhoto(600, 260),
				'link' => $box->getViewLink(),
				'loveds' => rand(0,5),
				'date' => $box->created_at,
			];
		}

		// Products
		$products = Product::findSerialized([
			'product_state' => Product::PRODUCT_STATE_ACTIVE,
			'only_active_persons' => true,
			'limit' => 10,
			'order_type' => 'new',
		]);

		foreach ($products as $product) {
			$items[] = [
				'id' => Utils::shortID(),
				'person' => $product->getDeviserPreview(),
				'action_type' => 'product_created',
				'action_name' => 'Added a new product',
				'title' => $product->name,
				'description' => $product->description,
				'photo' => $product->getImagePreview(600, 260),
				'link' => $product->getViewLink(),
				'loveds' => rand(0,5),
				'date' => $product->created_at,
			];
		}

		// Posts
		$posts = Post::findSerialized([
			'only_active_persons' => true,
			'limit' => 10,
		]);

		foreach ($posts as $post) {
			$items[] = [
				'id' => Utils::shortID(),
				'person' => $post->getPersonPreview(),
				'action_type' => 'post_created',
				'action_name' => 'Posted something new',
				'title' => null,
				'description' => $post->text,
				'photo' => $post->getImagePreview(600, 260),
				'link' => '#',
				'loveds' => rand(0,5),
				'date' => $post->created_at,
			];
		}

		usort($items, function($a, $b) {
			if ($a['date'] && $a['date']->sec > $b['date']->sec) {
				return false;
			}

			return true;
		});

		return [
			"items" => $items,
			"meta" => [
				"total_count" => count($items),
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}
}