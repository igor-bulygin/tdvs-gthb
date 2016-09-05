<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Product;
use Yii;
use yii\mongodb\ActiveQuery;
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

	public function actionIndex()
    {
	    // show only fields needed in this scenario
	    Product::setSerializeScenario(Product::SERIALIZE_SCENARIO_PUBLIC);

	    // set pagination values
	    $limit = Yii::$app->request->get('limit', 20);
	    $limit = ($limit < 1) ? 1 : $limit;
	    // not allow more than 100 products for request
	    $limit = ($limit > 100) ? 100 : $limit;
	    $page = Yii::$app->request->get('page', 1);
	    $page = ($page < 1) ? 1 : $page;
	    $offset = ($limit * ($page - 1));

	    $products = Product::findSerialized([
		    "name" => Yii::$app->request->get("name"), // search only in name attribute
		    "text" => Yii::$app->request->get("q"), // search in name, description, and more
		    "id" => Yii::$app->request->get("id"),
		    "deviser_id" => Yii::$app->request->get("deviser"),
	    	"categories" => Yii::$app->request->get("categories"),
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

}

