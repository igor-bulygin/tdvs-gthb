<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Person;
use app\models\Timeline;
use Yii;

class TimelineController extends AppPrivateController
{
	public function actionIndex()
	{
		// show only fields needed in this scenario
		Timeline::setSerializeScenario(Timeline::SERIALIZE_SCENARIO_PUBLIC);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 150);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		/* @var Person $connected */
		$connected = Yii::$app->user->identity;

		$items = [];
		if (!empty($connected->follow)) {
			$items = Timeline::findSerialized([
				'person_id' => $connected->follow,
				"limit" => $limit,
				"offset" => $offset,
				"order_by" => [
					'short_id' => SORT_ASC,
				],
			]);
		}

		return [
			"items" => $items,
			"meta" => [
				"total_count" => Timeline::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}
}