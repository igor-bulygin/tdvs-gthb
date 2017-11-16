<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Banner;
use Yii;
use yii\web\NotFoundHttpException;

class BannerController extends AppPrivateController
{
	public function actionView($bannerId)
	{
		Banner::setSerializeScenario(Banner::SERIALIZE_SCENARIO_ADMIN);
		$banner = Banner::findOneSerialized($bannerId);
		if (empty($banner)){
			throw new NotFoundHttpException(sprintf("Banner with id %s does not exists", $bannerId));
		}

		return $banner;
	}

	public function actionIndex()
	{
		// show only fields needed in this scenario
		Banner::setSerializeScenario(Banner::SERIALIZE_SCENARIO_ADMIN);

		// set pagination values
		$limit = Yii::$app->request->get('limit', 99999);
		$limit = ($limit < 1) ? 1 : $limit;
		$page = Yii::$app->request->get('page', 1);
		$page = ($page < 1) ? 1 : $page;
		$offset = ($limit * ($page - 1));

		$stories = Banner::findSerialized([
			"id" => Yii::$app->request->get("id"),
			"category_id" => Yii::$app->request->get('category_id'),
			"type" => Yii::$app->request->get('type'),
			"limit" => $limit,
			"offset" => $offset,
		]);

		return [
			"items" => $stories,
			"meta" => [
				"total_count" => Banner::$countItemsFound,
				"current_page" => $page,
				"per_page" => $limit,
			]
		];
	}

	public function actionCreate()
	{
		Banner::setSerializeScenario(Banner::SERIALIZE_SCENARIO_ADMIN);
		$banner = new Banner();

		if ($banner->load(Yii::$app->request->post(), '') && $banner->validate()) {

			$banner->save(false);

			Yii::$app->response->setStatusCode(201); // Created
			return $banner;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $banner->errors];
		}
	}

	public function actionUpdate($bannerId)
	{
		Banner::setSerializeScenario(Banner::SERIALIZE_SCENARIO_OWNER);

		$banner = Banner::findOneSerialized($bannerId); /* @var Banner $banner */

		if (empty($banner)) {
			throw new NotFoundHttpException(sprintf("Banner with id %s does not exists", $bannerId));
		}

		if ($banner->load(Yii::$app->request->post(), '') && $banner->validate(array_keys(Yii::$app->request->post()))) {

			$banner->save(false);

			Yii::$app->response->setStatusCode(201); // Created
			return $banner;
		} else {
			Yii::$app->response->setStatusCode(400); // Bad Request
			return ["errors" => $banner->errors];
		}
	}

	public function actionDelete($bannerId)
	{
		$banner = Banner::findOneSerialized($bannerId); /* @var Banner $banner */

		if (empty($banner)) {
			throw new NotFoundHttpException(sprintf("Banner with id %s does not exists", $bannerId));
		}

		$banner->delete();

		Yii::$app->response->setStatusCode(204); // No content

		return null;
	}
}