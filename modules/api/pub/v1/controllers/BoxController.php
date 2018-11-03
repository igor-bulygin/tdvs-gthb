<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Box;
use Yii;
use yii\web\NotFoundHttpException;

class BoxController extends AppPublicController
{

	public function actionView($boxId)
	{
		Box::setSerializeScenario(Box::SERIALIZE_SCENARIO_OWNER);
		$box = Box::findOneSerialized($boxId);
		if (empty($box)){
			throw new NotFoundHttpException(sprintf("Box with id %s does not exists", $boxId));
		}

		return $box;
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Box::setSerializeScenario(Box::SERIALIZE_SCENARIO_PUBLIC);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 99999);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$boxs = Box::findSerialized([
			"id" => Yii::$app->request->get("id"),
			"text" => Yii::$app->request->get("q"), // search in name, description, and more
			"person_id" => Yii::$app->request->get("person_id"),
			"product_id" => Yii::$app->request->get("product_id"),
			"ignore_empty_boxes" => Yii::$app->request->get("ignore_empty_boxes", false),
			"only_active_persons" => true,
			"countries" => Yii::$app->request->get("countries"),
			"limit" => $limit,
			"offset" => $offset,
		]);

		return [
			"items" => $boxs,
			"meta" => [
				"total_count" => Box::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}

    /**
     * @return array
     * @throws \Exception
     * Function returns number of boxes based on request params and type of response ("box")
     */
	public function actionCount()
    {
        // show only fields needed in this scenario
        Box::setSerializeScenario(Box::SERIALIZE_SCENARIO_COUNT);

        $query = Box::findSerialized([
            "id" => Yii::$app->request->get("id"),
            "text" => Yii::$app->request->get("q"), // search in name, description, and more
            "person_id" => Yii::$app->request->get("person_id"),
            "product_id" => Yii::$app->request->get("product_id"),
            "ignore_empty_boxes" => Yii::$app->request->get("ignore_empty_boxes", false),
            "only_active_persons" => true,
            "countries" => Yii::$app->request->get("countries"),
        ]);
        $count = Box::$countItemsFound;
        return [
            "type"  => 'box',
            "count" => $count,
        ];
    }

}