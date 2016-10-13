<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Category;
use app\models\Country;
use app\models\Lang;
use PestJSON;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class LocationController extends Controller {

    public function actionIndex()
    {
	    $text = Yii::$app->request->get("q");
	    $view = Yii::$app->request->get("view", "parsed");

	    $pest = new PestJSON('https://maps.googleapis.com/maps/api/place/autocomplete');

	    // TODO Move API Key to .env file
	    $result = $pest->get(sprintf('/json?input=%s&types=(cities)&key=AIzaSyCJH-hVpG5hT_wooQ1baYtRuR4NlrG8dyw', urlencode($text)));
	    switch ($view) {
		    case "debug":
			    $predictions = $result;
			    break;
		    case "parsed":
		    default:
			    $predictions = [];
			    foreach ($result["predictions"] as $item) {
			    	$countryName = $item["terms"][count($item["terms"]) - 1]["value"];
			    	$country = Country::findOne(["country_name.en-US" => $countryName]);
				    if ($country) {
				    	$countryCode = $country->country_code;
					    $predictions[] = [
						    "description" => $item["description"],
						    "city" => $item["terms"][0]["value"],
						    "country_code" => $countryCode,
						    "country_name" => $countryName,
						    "terms" => $item["terms"],
					    ];
				    }
				}
			    break;
	    }

	    return [
		    "items" => $predictions,
		    "meta" => [
			    "total_count" => count($predictions),
			    "current_page" => 1,
			    "per_page" => count($predictions),
		    ]
	    ];
    }

}

