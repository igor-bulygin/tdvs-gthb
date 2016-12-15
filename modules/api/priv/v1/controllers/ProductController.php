<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Product2;
use Exception;
use Yii;
use yii\web\BadRequestHttpException;

class ProductController extends AppPrivateController
{

    public function actionView($id)
    {
        Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_OWNER);
        $product = Product2::findOneSerialized($id);

        return $product;
    }

    public function actionIndex()
    {
        return ["action" => "index"];
    }

    public function actionCreate()
    {
        Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_OWNER);
        $product = new Product2();

        try {
            $product->setScenario($this->getDetermineScenario($product));
            if ($product->load(Yii::$app->request->post(), '') && $product->validate()) {

                $product->save();

                Yii::$app->response->setStatusCode(201); // Created
                return $product;
            } else {
                Yii::$app->response->setStatusCode(400); // Bad Request
                return ["errors" => $product->errors];
            }
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    public function actionUpdate($id)
    {
        Product2::setSerializeScenario(Product2::SERIALIZE_SCENARIO_OWNER);
        /** @var Product2 $product */
        $product = Product2::findOneSerialized($id);
        if (!$product) {
            throw new BadRequestHttpException('Product not found');
        }

        try {
            $product->setScenario($this->getDetermineScenario($product));
            if ($product->load(Yii::$app->request->post(), '') && $product->validate()) {

                $product->save();

                Yii::$app->response->setStatusCode(200); // Ok
                return $product;
            } else {
                Yii::$app->response->setStatusCode(400); // Bad Request
                return ["errors" => $product->errors];
            }
        } catch (Exception $e) {
            throw new BadRequestHttpException($e->getMessage());
        }
    }

    public function actionDelete($id)
    {
        try {
            /** @var Product2 $product */
            $product = Product2::findOneSerialized($id);
            if (!$product) {
                throw new BadRequestHttpException('Product not found');
            }
            $product->delete();
            Yii::$app->response->setStatusCode(204); // No content

            return null;
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
        $product_state = Yii::$app->request->post('product_state', Product2::PRODUCT_STATE_DRAFT);

        // can't change from "active" to "draft"
        if ($product_state == Product2::PRODUCT_STATE_ACTIVE || $product->product_state == Product2::PRODUCT_STATE_ACTIVE) {
            // it is updating a active product (or a product that want to be active)
            $scenario = Product2::SCENARIO_PRODUCT_PUBLIC;
        } else {
            // it is updating a draft product
            $scenario = Product2::SCENARIO_PRODUCT_DRAFT;
        }

        return $scenario;
    }
}