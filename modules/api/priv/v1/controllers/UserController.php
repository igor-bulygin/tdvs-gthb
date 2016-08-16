<?php

namespace app\modules\api\priv\v1\controllers;

use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class UserController extends Controller {

    public function actionIndex()
    {
        return;
    }

}

