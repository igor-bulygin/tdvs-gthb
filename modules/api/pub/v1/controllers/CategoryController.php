<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Category;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;


class CategoryController extends Controller {

    public function actionIndex()
    {
        // set the scenario to serialize objects
        Category::setSerializeScenario(CActiveRecord::SERIALIZE_SCENARIO_PUBLIC);

        return Category::getSerialized();
    }

}

