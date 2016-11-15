<?php

namespace app\modules\api\pub\v1\controllers;

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

	    $sizeCharts = SizeChart::findSerialized([
			"scope" => Yii::$app->request->get("scope", "all"),
			"limit" => $limit,
		    "offset" => $offset,
	    ]);
		$total = SizeChart::$countItemsFound;

	    return [
		    "items" => $sizeCharts,
		    "meta" => [
			    "total_returned" => count($sizeCharts),
			    "total_count" => $total,
			    "current_page" => $page,
			    "per_page" => $limit,
		    ]
	    ];
    }
}

