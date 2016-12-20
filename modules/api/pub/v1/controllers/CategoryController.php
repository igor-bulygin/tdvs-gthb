<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Category;
use Yii;


class CategoryController extends AppPublicController {

    public function actionIndex()
    {
        // set the scenario to serialize objects
        Category::setSerializeScenario(Category::SERIALIZE_SCENARIO_PUBLIC);

	    // set pagination values
	    $limit = Yii::$app->request->get('limit', 100);
	    $limit = ($limit < 1) ? 1 : $limit;
	    $page = Yii::$app->request->get('page', 1);
	    $page = ($page < 1) ? 1 : $page;
	    $offset = ($limit * ($page - 1));

	    $categories = Category::findSerialized([
	        "scope" => Yii::$app->request->get("scope", "roots"),
		    "limit" => $limit,
		    "offset" => $offset,
	    ]);

	    return [
		    "items" => $categories,
		    "meta" => [
			    "total_count" => count($categories),
			    "current_page" => $page,
			    "per_page" => $limit,
		    ]
	    ];
    }
}

