<?php

namespace app\modules\api\priv\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Person;
use app\models\Product;
use app\modules\api\priv\v1\forms\UploadForm;
use Yii;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;
use yii\web\UploadedFile;
use yii\web\User;

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
		return ["action" => "index"];
	}

	public function actionCreate()
	{
		return ["action" => "create"];
	}

	public function actionUpdate($id)
	{
		$product = Product::findOneSerialized($id);
		if (!$product) {
			throw new BadRequestHttpException('Product not found');
		}

		/** @var Person $deviser */
		$deviser = $this->getPerson();

//        $data = Yii::$app->request->post();
//        print_r($data);

		$product->setScenario(Product::SCENARIO_PRODUCT_UPDATE_DRAFT);
		if ($product->load(Yii::$app->request->post(), '') && $product->save()) {
			// handle success

			// TODO: return the deviser data, only for test. remove when finish.
//            Yii::$app->response->setStatusCode(204); // Success, without body
			Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_OWNER);
			return $product;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $product->errors];
		}
	}

	public function actionDelete($id)
	{
		return ["action" => "delete " . $id];
	}

	/**
	 * Get validation scenario from request param
	 *
	 * @throws BadRequestHttpException
	 * @return string
	 */
	private function getScenarioFromRequest()
	{
		// get scenario to use in validations, from request
		$scenario = Yii::$app->request->post('scenario', Person::SCENARIO_DEVISER_UPDATE_PROFILE);

		// check that is a valid scenario for this controller
		if (!in_array($scenario, [
			Person::SCENARIO_DEVISER_CREATE_DRAFT,
			Person::SCENARIO_DEVISER_UPDATE_DRAFT,
			Person::SCENARIO_DEVISER_PUBLISH_PROFILE,
			Person::SCENARIO_DEVISER_UPDATE_PROFILE,
			Person::SCENARIO_DEVISER_PRESS_UPDATE,
			Person::SCENARIO_DEVISER_VIDEOS_UPDATE,
			Person::SCENARIO_DEVISER_FAQ_UPDATE,
		])
		) {
			throw new BadRequestHttpException('Invalid scenario');
		}

		return $scenario;
	}
}

