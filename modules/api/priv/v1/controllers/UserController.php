<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Person;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class UserController extends Controller {

    public function init() {
        parent::init();

	    // TODO: retrieve current identity from one of the available authentication methods in Yii
	    $deviser_id = Yii::$app->request->isGet ? Yii::$app->request->get("deviser_id") : Yii::$app->request->post("deviser_id");
	    Yii::$app->user->login(Person::findOne(["short_id" => $deviser_id]));
    }

    public function actionView()
    {
        /** @var Person $user */
        $user = \Yii::$app->user->getIdentity();

        return $user;
    }

    public function actionUpdate()
    {
        return ["action" => "update (user)"];
    }

}

