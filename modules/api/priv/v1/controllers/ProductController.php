<?php

namespace app\modules\api\priv\v1\controllers;

use app\helpers\Utils;
use app\models\Product;
use app\models\Person;
use app\models\ProductComment;
use app\models\ProductCommentReply;
use app\models\ProductCommentHelpful;
use app\models\Timeline;
use Yii;
use yii\web\BadRequestHttpException;
use yii\web\MethodNotAllowedHttpException;
use yii\web\NotFoundHttpException;

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
			"order_type" => Yii::$app->request->get("order_type"),
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

			$timeline = new Timeline();
			$timeline->person_id = $product->deviser_id;
			$timeline->target_id = $product->short_id;
			$timeline->action_type = Timeline::ACTION_PRODUCT_CREATED;
			$timeline->date = new \MongoDate();
			$timeline->save();

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
			throw new NotFoundHttpException('Product not found');
		}

		$newProductState = Yii::$app->request->post('product_state', $product->product_state);
		$this->checkProductState($product, $newProductState); // check for allowed new account state only

		// only validate received fields (only if we are not changing the state)
		$validateFields = $product->product_state == $newProductState ? array_keys(Yii::$app->request->post()) : null;

		$product->setScenario($this->getScenarioFromRequest($product));

		if ($product->load(Yii::$app->request->post(), '') && $product->validate($validateFields)) {

			$product->save(false);

			$timeline = new Timeline();
			$timeline->person_id = $product->deviser_id;
			$timeline->target_id = $product->short_id;
			$timeline->action_type = Timeline::ACTION_PRODUCT_UPDATED;
			$timeline->date = new \MongoDate();
			$timeline->save();

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
			throw new NotFoundHttpException('Product not found');
		}

		if ($product->hasOrders()) {
			throw new MethodNotAllowedHttpException('You cannot delete a product with orders');
		}

		$product->delete();
		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}

	public function actionComment($id)
	{
		// show only fields needed in this scenario
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_OWNER);

		/** @var Product $product */
		$product = Product::findOneSerialized($id);
		if (!$product) {
			throw new NotFoundHttpException('Product not found');
		}

		if ($product->product_state != Product::PRODUCT_STATE_ACTIVE && !$product->getDeviser()->isPersonEditable()) {
			Yii::$app->response->setStatusCode(204); // No content
			return null;
		}

		$product->setScenario(Product::SCENARIO_PRODUCT_COMMENT);

		$productComment = new ProductComment();
		$productComment->person_id = Yii::$app->user->id;
		$productComment->short_id = Utils::shortID(8);
		$productComment->created_at = new \MongoDate();
		$productComment->setParentObject($product);

		if ($productComment->load(Yii::$app->request->post(), '') && $productComment->validate() && !$product->hasErrors()) {
			$productComment->person_id = Yii::$app->user->id;
			$product->commentsMapping[] = $productComment;

			// Refresh properties from the embed
			$product->refreshFromEmbedded();
			$product->save(false);

			Yii::$app->response->setStatusCode(201); // Created
			return $product;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request

			return ["errors" => $product->errors];
		}
	}

	public function actionCommentReply($product_id, $comment_id)
	{
		// show only fields needed in this scenario
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_OWNER);

		/** @var Product $product */
		$product = Product::findOneSerialized($product_id);
		if (!$product) {
			throw new NotFoundHttpException('Product not found');
		}

		if ($product->product_state != Product::PRODUCT_STATE_ACTIVE && !$product->getDeviser()->isPersonEditable()) {
			Yii::$app->response->setStatusCode(204); // No content
			return null;
		}

		$comments = $product->commentsMapping;
		$key = null;
		foreach ($comments as $i => $comment) {
			if ($comment->short_id == $comment_id) {
				$key = $i;
				break;
			}
		}
		if (!isset($key)) {
			throw new NotFoundHttpException('Comment not found');
		}
		$parentComment = $comments[$key];

		$product->setScenario(Product::SCENARIO_PRODUCT_COMMENT_REPLY);

		$productCommentReply = new ProductCommentReply();
		$productCommentReply->short_id = Utils::shortID(8);
		$productCommentReply->created_at = new \MongoDate();
		$productCommentReply->setParentObject($parentComment);

		if ($productCommentReply->load(Yii::$app->request->post(), '') && $productCommentReply->validate() && !$parentComment->hasErrors()) {
			$productCommentReply->person_id = Yii::$app->user->id;
			$parentComment->repliesInfo[] = $productCommentReply;
			$parentComment->refreshFromEmbedded();
			$product->refreshFromEmbedded();
			$product->save(false);

			Yii::$app->response->setStatusCode(201); // Created
			return $product;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request

			return ["errors" => $product->errors];
		}
	}

	public function actionCommentHelpful($product_id, $comment_id, $helpful)
	{
		// show only fields needed in this scenario
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_OWNER);

		/** @var Product $product */
		$product = Product::findOneSerialized($product_id);
		if (!$product) {
			throw new NotFoundHttpException('Product not found');
		}

		if ($product->product_state != Product::PRODUCT_STATE_ACTIVE && !$product->getDeviser()->isPersonEditable()) {
			Yii::$app->response->setStatusCode(204); // No content
			return null;
		}

		$comments = $product->commentsMapping;
		$key = null;
		foreach ($comments as $i => $comment) {
			if ($comment->short_id == $comment_id) {
				$key = $i;
				break;
			}
		}
		if (!isset($key)) {
			throw new NotFoundHttpException('Comment not found');
		}
		$parentComment = $comments[$key];

		$product->setScenario(Product::SCENARIO_PRODUCT_COMMENT_HELPFUL);

		$productCommentHelpful = new ProductCommentHelpful();
		$productCommentHelpful->setParentObject($parentComment);

		if ($productCommentHelpful->load(Yii::$app->request->post(), '')) {
			if($parentComment['helpfuls']){
				if ($helpful == 'yes'){
						if($parentComment['helpfuls']['yes']){
							$productCommentHelpful['yes'] = $parentComment['helpfuls']['yes'][0] + 1;
							$productCommentHelpful['no'] = $parentComment['helpfuls']['no'][0];
						}
						else{
							$productCommentHelpful['yes'] = 1;
							$productCommentHelpful['no'] = $parentComment['helpfuls']['no'][0];
						}
					}
				else{
					if($parentComment['helpfuls']['no']){
						$productCommentHelpful['no'] = $parentComment['helpfuls']['no'][0] + 1;
						$productCommentHelpful['yes'] = $parentComment['helpfuls']['yes'][0];
					}
					else{
						$productCommentHelpful['no'] = 1;
						$productCommentHelpful['yes'] = $parentComment['helpfuls']['yes'][0];
					}
				}
			}
			else{
				$parentComment['helpfuls'] = [['yes'], ['no']];
				if ($helpful == 'yes'){
					$productCommentHelpful['yes'] = 1;
				}
				else{
					$productCommentHelpful['no'] = 1;
				}
			}
			$parentComment->helpfulsInfo = $productCommentHelpful;
			$parentComment->setAttribute('helpfuls', $productCommentHelpful);
			$product->refreshFromEmbedded();
			$product->save(false);
	
			Yii::$app->response->setStatusCode(201); // Created
			return $product;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request

			return ["errors" => $product->errors];
		}
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