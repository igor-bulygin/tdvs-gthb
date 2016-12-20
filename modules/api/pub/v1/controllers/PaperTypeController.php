<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\PaperType;
use Yii;


class PaperTypeController extends AppPublicController {

    public function actionIndex()
	{
		$items = PaperType::getSerialized();

		return [
				"items" => $items,
				"meta" => [
					"total" => count($items),
				]
		];
	}
}

