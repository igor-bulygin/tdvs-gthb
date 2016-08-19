<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Category;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class CategoryController extends Controller {

    public function actionIndex()
    {
        return Category::getSerializedPublic();
    }

}

