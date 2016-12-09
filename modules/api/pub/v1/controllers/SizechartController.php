<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Person;
use app\models\SizeChart;
use Yii;
use yii\rest\Controller;


class SizechartController extends Controller {

    public function actionIndex()
    {
        // set the scenario to serialize objects
        SizeChart::setSerializeScenario(SizeChart::SERIALIZE_SCENARIO_PUBLIC);

	    // set pagination values
	    $limit = Yii::$app->request->get('limit', 100);
	    $limit = ($limit < 1) ? 1 : $limit;
	    $page = Yii::$app->request->get('page', 1);
	    $page = ($page < 1) ? 1 : $page;
	    $offset = ($limit * ($page - 1));

		$criteria = [
				"scope" => Yii::$app->request->get("scope", "all"),
				"limit" => $limit,
				"offset" => $offset,
		];

		if (!Yii::$app->user->isGuest) {
			// If there is a connected deviser, we also return custom sizecharts
			$person = Yii::$app->user->getIdentity(); /* @var Person $person */
			if ($person->isDeviser()) {
				$criteria["deviser_id"] = $person->short_id;
			}
		}
	    $sizeCharts = SizeChart::findSerialized($criteria);
	    return [
		    "items" => $sizeCharts,
		    "meta" => [
			    "total_returned" => count($sizeCharts),
			    "total_count" => SizeChart::$countItemsFound,
			    "current_page" => $page,
			    "per_page" => $limit,
		    ]
	    ];
    }
}

