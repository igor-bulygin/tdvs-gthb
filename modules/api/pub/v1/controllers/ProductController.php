<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Product2;
use Yii;
use yii\web\NotFoundHttpException;

class ProductController extends AppPublicController {

	public function actionView($id)
	{
		// show only fields needed in this scenario
		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_PUBLIC);

		/** @var Product2 $product */
		$product = Product2::findOneSerialized($id);
		if (!$product) {
			throw new NotFoundHttpException('Product not found');
		}

		if ($product->product_state != Product2::PRODUCT_STATE_ACTIVE && !$product->getDeviser()->isPersonEditable()) {
			Yii::$app->response->setStatusCode(204); // No content
			return null;
		}

		return $product;
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_PUBLIC);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 99999);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$products = Product2::findSerialized([
				"id" => Yii::$app->request->get("id"),
				"name" => Yii::$app->request->get("name"), // search only in name attribute
				"text" => Yii::$app->request->get("q"), // search in name, description, and more
				"deviser_id" => Yii::$app->request->get("deviser"),
				"categories" => Yii::$app->request->get("categories"),
				"product_state" => Product2::PRODUCT_STATE_ACTIVE,
				"limit" => $limit,
				"offset" => $offset,
		]);

		return [
				"items" => $products,
				"meta" => [
						"total_count" => Product2::$countItemsFound,
						"current_page" => $page,
						"per_page" => $limit,
				]
		];
	}

}

