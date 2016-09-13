<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Category;
use app\models\Country;
use app\models\Lang;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class LanguageController extends Controller {

    public function actionIndex()
    {
    	$languages = Lang::findSerialized();

	    return [
		    "items" => $languages,
		    "meta" => [
			    "total_count" => count($languages),
			    "current_page" => 1,
			    "per_page" => count($languages),
		    ]
	    ];
    }

}

