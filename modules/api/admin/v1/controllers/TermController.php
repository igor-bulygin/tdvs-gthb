<?php

namespace app\modules\api\admin\v1\controllers;

use app\models\Term;
use Yii;
use yii\rest\Controller;

class TermController extends Controller {

//    public $modelClass = 'app\models\Term';

    public function actionIndex()
    {
        // set the scenario to serialize objects
        Term::setSerializeScenario(Term::SERIALIZE_SCENARIO_ADMIN);

        return Term::getSerialized();

    }

    public function actionView($id)
    {
        return ["action" => "view", "id" => $id];
    }

    public function actionCreate()
    {
        Yii::$app->response->setStatusCode(201); // Created
        return ["action" => "create"];
    }

    public function actionUpdate($id)
    {
        Yii::$app->response->setStatusCode(204); // Success, without body
//        return ["action" => "update", "id" => $id];
    }

    public function actionDelete($id)
    {
        Yii::$app->response->setStatusCode(204); // Success, without body
//        return ["action" => "delete", "id" => $id];
    }
}

