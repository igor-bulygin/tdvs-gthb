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
use yii\web\User;

class DeviserController extends Controller {

    public function init() {
        parent::init();

        // TODO: retrieve current identity from one of the available authentication methods in Yii
        Yii::$app->user->login(Person::findOne(["short_id" => "13cc33k"]));
    }

    public function actionView()
    {
        /** @var Person $deviser */
        $deviser = Yii::$app->user->getIdentity();

        return $deviser;
    }

    public function actionUpdate()
    {
        /** @var Person $deviser */
        $deviser = Yii::$app->user->getIdentity();

//        $data = Yii::$app->request->post();
//        print_r($data);

        $deviser->setScenario(Person::SCENARIO_DEVISER_PROFILE_UPDATE);
        if ($deviser->load(Yii::$app->request->post()) && $deviser->save()) {
            // handle success

            // TODO: return the deviser data, only for test. remove when finish.
//            Yii::$app->response->setStatusCode(204); // Success, without body
            return ["deviser" => $deviser];
        } else {
            return ["errors" => $deviser->errors];
        }
    }
}

