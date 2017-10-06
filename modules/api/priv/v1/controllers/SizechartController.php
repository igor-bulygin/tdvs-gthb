<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Person;
use app\models\SizeChart;
use Yii;
use yii\web\NotFoundHttpException;
use yii\web\UnauthorizedHttpException;


class SizechartController extends AppPrivateController {

    public function actionIndex()
    {
        // set the scenario to serialize objects
        SizeChart::setSerializeScenario(SizeChart::SERIALIZE_SCENARIO_PUBLIC);

	    // set pagination values
	    $limit = Yii::$app->request->get('limit', 99999);
	    $limit = ($limit < 1) ? 1 : $limit;
	    $page = Yii::$app->request->get('page', 1);
	    $page = ($page < 1) ? 1 : $page;
	    $offset = ($limit * ($page - 1));

		$criteria = [
				"deviser_id" => Yii::$app->request->get("deviser_id"),
				"scope" => Yii::$app->request->get("scope", "all"),
				"limit" => $limit,
				"offset" => $offset,
		];

		if (!empty($criteria['deviser_id'])) {
			$deviser = Person::findOne(['short_id' => $criteria['deviser_id']]);
			if ($deviser && !$deviser->isDeviserEditable()) {
				throw new UnauthorizedHttpException();
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

	public function actionCreate()
	{
		SizeChart::setSerializeScenario(SizeChart::SERIALIZE_SCENARIO_OWNER);
		$sizeChart = new SizeChart();

		if ($sizeChart->load(Yii::$app->request->post(), '') && $sizeChart->validate()) {

			$sizeChart->short_id = null;
			$sizeChart->save(false);

			Yii::$app->response->setStatusCode(201); // Created
			return $sizeChart;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $sizeChart->errors];
		}
	}

	public function actionUpdate($sizechartId)
	{
		SizeChart::setSerializeScenario(SizeChart::SERIALIZE_SCENARIO_OWNER);
		/** @var SizeChart $sizeChart */
		$sizeChart = SizeChart::findOneSerialized($sizechartId);
		if (!$sizeChart) {
			throw new NotFoundHttpException('SizeChart not found');
		}

		if ($sizeChart->load(Yii::$app->request->post(), '') && $sizeChart->validate()) {

			$sizeChart->save(false);

			Yii::$app->response->setStatusCode(200); // Ok
			return $sizeChart;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $sizeChart->errors];
		}
	}
}

