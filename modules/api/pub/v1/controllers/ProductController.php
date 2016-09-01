<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Product;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class ProductController extends Controller {

	public function actionView($id)
	{
		// show only fields needed in this scenario
		Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);

		/** @var Product $product */
		$product = Product::findOneSerialized($id);
		if (!$product) {
			throw new NotFoundHttpException('Product not found');
		}

		return $product;
	}

//	public function actionIndex()
//    {
//	    return ["action" => "index"];
//    }

}

