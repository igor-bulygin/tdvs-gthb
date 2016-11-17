<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Category;
use app\models\MetricType;
use app\models\MetricUnit;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;


class MetricController extends Controller {

    public function actionIndex()
    {
		$object = new \stdClass();
		$units = MetricType::UNITS;
		$object->size = $units[MetricType::SIZE];
		$object->weight = $units[MetricType::WEIGHT];

	    return $object;
    }
}

