<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Product;
use app\models\Product2;
use Yii;
use yii\web\NotFoundHttpException;

class ProductController extends AppPublicController {

	public function actionView($id)
	{
		// show only fields needed in this scenario
		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_PUBLIC);

		/** @var Product $product */
		$product = Product2::findOneSerialized($id);
		if (!$product) {
			throw new NotFoundHttpException('Product not found');
		}

		return $product;
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_PUBLIC);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 20);
		$limit = ($limit < 1) ? 1 : $limit;
		// not allow more than 100 products for request
//	    $limit = ($limit > 100) ? 100 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$products = Product2::findSerialized([
				"id" => Yii::$app->request->get("id"),
				"name" => Yii::$app->request->get("name"), // search only in name attribute
				"text" => Yii::$app->request->get("q"), // search in name, description, and more
				"deviser_id" => Yii::$app->request->get("deviser"),
				"categories" => Yii::$app->request->get("categories"),
				"product_state" => Yii::$app->request->get("product_state"),
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

