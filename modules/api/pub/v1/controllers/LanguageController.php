<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Lang;
use Yii;

class LanguageController extends AppPublicController {

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

