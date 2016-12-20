<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\MetricType;
use Yii;


class MetricController extends AppPublicController {

    public function actionIndex()
    {
		return MetricType::getSerialized();
    }
}

