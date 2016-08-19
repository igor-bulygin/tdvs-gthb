<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Category;
use app\models\Country;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class CountryController extends Controller {

    public function actionIndex()
    {
        return Country::getSerializedPublic();
    }

}

