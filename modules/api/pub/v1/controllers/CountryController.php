<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Category;
use app\models\Country;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class CountryController extends Controller {

    public function actionIndex()
    {
        // set the scenario to serialize objects
        Country::setSerializeScenario(Country::SERIALIZE_SCENARIO_PUBLIC);

	    // set pagination values
	    $limit = Yii::$app->request->get('limit', 9999);
	    $limit = ($limit < 1) ? 1 : $limit;
	    $page = Yii::$app->request->get('page', 1);
	    $page = ($page < 1) ? 1 : $page;
	    $offset = ($limit * ($page - 1));

	    $countries = Country::findSerialized([
		    "name" => Yii::$app->request->get("name"), // search only in name attribute
		    "limit" => $limit,
		    "offset" => $offset,
	    ]);

	    return [
		    "items" => $countries,
		    "meta" => [
			    "total_count" => count($countries),
			    "current_page" => $page,
			    "per_page" => $limit,
		    ]
	    ];
    }

}

