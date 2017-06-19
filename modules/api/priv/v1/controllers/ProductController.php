<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Product;
use Yii;
use yii\web\BadRequestHttpException;

class ProductController extends AppPrivateController
{

	public function actionView($id)
	{
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_OWNER);
		$product = Product::findOneSerialized($id);

		return $product;
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 100);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$products = Product::findSerialized([
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
				"total_count" => Product::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}

	public function actionCreate()
	{
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_OWNER);
		$product = new Product();

		$product->setScenario($this->getScenarioFromRequest($product));
		if ($product->load(Yii::$app->request->post(), '') && $product->validate()) {

			$product->save(false);

			Yii::$app->response->setStatusCode(201); // Created
			return $product;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $product->errors];
		}
	}

	public function actionUpdate($id)
	{
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_OWNER);
		/** @var Product $product */
		$product = Product::findOneSerialized($id);
		if (!$product) {
			throw new BadRequestHttpException('Product not found');
		}

		$newProductState = Yii::$app->request->post('product_state', $product->product_state);
		$this->checkProductState($product, $newProductState); // check for allowed new account state only

		// only validate received fields (only if we are not changing the state)
		$validateFields = $product->product_state == $newProductState ? array_keys(Yii::$app->request->post()) : null;

		$product->setScenario($this->getScenarioFromRequest($product));

		if ($product->load(Yii::$app->request->post(), '') && $product->validate($validateFields)) {

			$product->save(false);

			Yii::$app->response->setStatusCode(200); // Ok
			return $product;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $product->errors];
		}
	}

	public function actionDelete($id)
	{
		/** @var Product $product */
		$product = Product::findOneSerialized($id);
		if (!$product) {
			throw new BadRequestHttpException('Product not found');
		}
		$product->delete();
		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}

	/**
	 * Get validation scenario from request param
	 *
	 * @param Product $product
	 *
	 * @return string
	 * @throws BadRequestHttpException
	 */
	private function getScenarioFromRequest(Product $product)
	{
		// get scenario to use in validations, from request
		$product_state = Yii::$app->request->post('product_state', Product::PRODUCT_STATE_DRAFT);

		// can't change from "active" to "draft"
		if ($product_state == Product::PRODUCT_STATE_ACTIVE || $product->product_state == Product::PRODUCT_STATE_ACTIVE) {
			// it is updating a active product (or a product that want to be active)
			$scenario = Product::SCENARIO_PRODUCT_PUBLIC;
		} else {
			// it is updating a draft product
			$scenario = Product::SCENARIO_PRODUCT_DRAFT;
		}

		return $scenario;
	}

	/**
	 * Logic for assign new product state.
	 * Only allow change state to "active", otherwise, raise an exception
	 *
	 * @param Product $product
	 * @param $productState
	 *
	 * @throws BadRequestHttpException
	 */
	private function checkProductState(Product $product, $productState)
	{
		if (!empty($productState)) {
			switch ($product->product_state) {
				case Product::PRODUCT_STATE_DRAFT:
					if (!in_array($productState, [Product::PRODUCT_STATE_DRAFT, Product::PRODUCT_STATE_ACTIVE])) {
						throw new BadRequestHttpException('Invalid product state');
					}
					break;
				case Product::PRODUCT_STATE_ACTIVE:
					if (!in_array($productState, [Product::PRODUCT_STATE_ACTIVE])) {
						throw new BadRequestHttpException('Invalid product state');
					}
					break;
			}
		}
	}
}