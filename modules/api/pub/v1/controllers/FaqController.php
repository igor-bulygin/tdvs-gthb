<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class FaqController extends Controller {

    public function actionIndex()
    {
        // set the view to serialize objects
        Faq::setSerializeView(CActiveRecord::SERIALIZE_VIEW_PUBLIC);

        return Faq::getSerialized();
    }

}

