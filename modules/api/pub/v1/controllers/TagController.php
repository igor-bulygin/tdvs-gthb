<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Tag;
use Yii;


class TagController extends AppPublicController {

    public function actionIndex()
    {
        // set the scenario to serialize objects
        Tag::setSerializeScenario(Tag::SERIALIZE_SCENARIO_PUBLIC);

	    // set pagination values
	    $limit = Yii::$app->request->get('limit', 99999);
	    $limit = ($limit < 1) ? 1 : $limit;
	    $page = Yii::$app->request->get('page', 1);
	    $page = ($page < 1) ? 1 : $page;
	    $offset = ($limit * ($page - 1));

	    $tags = Tag::findSerialized([
			"scope" => Yii::$app->request->get("scope", "all"),
			"limit" => $limit,
		    "offset" => $offset,
	    ]);

	    return [
		    "items" => $tags,
		    "meta" => [
				"total_returned" => count($tags),
				"total_count" => Tag::$countItemsFound,
			    "current_page" => $page,
			    "per_page" => $limit,
		    ]
	    ];
    }
}

