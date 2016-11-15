<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Tag;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;


class TagController extends Controller {

    public function actionIndex()
    {
        // set the scenario to serialize objects
        Tag::setSerializeScenario(Tag::SERIALIZE_SCENARIO_PUBLIC);

	    // set pagination values
	    $limit = Yii::$app->request->get('limit', 100);
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

