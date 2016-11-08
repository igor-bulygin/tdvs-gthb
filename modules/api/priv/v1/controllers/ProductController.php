<?php

namespace app\modules\api\priv\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Person;
use app\models\Product;
use app\models\Product2;
use app\modules\api\priv\v1\forms\UploadForm;
use Exception;
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
		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_ADMIN);
		$product = Product2::findOneSerialized($id);

		return $product;
	}

	public function actionIndex()
	{
		return ["action" => "index"];
	}

	public function actionCreate()
	{
		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_PUBLIC);
		$product = new Product2();
		$product->setScenario(Product2::SCENARIO_PRODUCT_CREATE_DRAFT);

		if ($product->load(Yii::$app->request->post(), '') && $product->validate()) {
			// save the invitation
			$product->save();

			Yii::$app->response->setStatusCode(201); // Created
			return $product;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $product->errors];
		}
	}

	public function actionUpdate($id)
	{
		Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_ADMIN);
		/** @var Product2 $product */
		$product = Product2::findOneSerialized($id);
		if (!$product) {
			throw new BadRequestHttpException('Product not found');
		}

		try {
			$product->setScenario($this->getDetermineScenario($product));
			if ($product->load(Yii::$app->request->post(), '') && $product->save()) {
				// handle success

				// TODO: return the deviser data, only for test. remove when finish.
	//            Yii::$app->response->setStatusCode(204); // Success, without body
				return $product;
			} else {
				Yii::$app->response->setStatusCode(400); // Bad Request
				return ["errors" => $product->errors];
			}
		} catch (Exception $e) {
			throw new BadRequestHttpException($e->getMessage());
		}
	}

    /**
     * Get validation scenario from request param
     *
     * @param Product2 $product
     * @return string
     * @throws BadRequestHttpException
     */
    private function getDetermineScenario(Product2 $product)
    {
        // get scenario to use in validations, from request
        $scenario = Yii::$app->request->post('scenario', Person::SCENARIO_DEVISER_UPDATE_PROFILE);

        // check that is a valid scenario for this controller
        if (!in_array($scenario, [
            Product2::SCENARIO_PRODUCT_CREATE_DRAFT,
            Product2::SCENARIO_PRODUCT_UPDATE_DRAFT,
            Product2::SCENARIO_PRODUCT_PUBLISH,
            Product2::SCENARIO_PRODUCT_UPDATE,
        ])
        ) {
            throw new BadRequestHttpException('Invalid scenario');
        }

        // can't change from "active" to "draft"
        if ($product->account_state == Product2::ACCOUNT_STATE_ACTIVE) {
            // it is updating a active profile (or a profile that want to be active)
            $scenario = Product2::SCENARIO_PRODUCT_UPDATE;
        } else {
            // it is updating a draft profile
            $scenario = Product2::SCENARIO_PRODUCT_UPDATE_DRAFT;
        }

        return $scenario;
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
		])
		) {
			throw new BadRequestHttpException('Invalid scenario');
		}

		return $scenario;
	}
}

