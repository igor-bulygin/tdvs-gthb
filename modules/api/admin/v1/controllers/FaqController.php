<?php

namespace app\modules\api\admin\v1\controllers;

use app\helpers\CActiveRecord;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class FaqController extends Controller {

//    public $modelClass = 'app\models\Term';

    public function actionIndex()
    {
        // set the scenario to serialize objects
        Faq::setSerializeScenario(Faq::SERIALIZE_SCENARIO_ADMIN);

        return Faq::getSerialized();
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

    /**
     * Checks the privilege of the current user.
     *
     * This method should be overridden to check whether the current user has the privilege
     * to run the specified action against the specified data model.
     * If the user does not have access, a [[ForbiddenHttpException]] should be thrown.
     *
     * @param string $action the ID of the action to be executed
     * @param \yii\base\Model $model the model to be accessed. If null, it means no specific model is being accessed.
     * @param array $params additional parameters
     * @throws ForbiddenHttpException if the user does not have access
     */

    public function checkAccess($action, $model = null, $params = [])
    {
        // check if the user can access $action and $model
        // throw ForbiddenHttpException if access should be denied
    }

//    public function behaviors() {
//        return [
//            'format' => [
//                'class' => ContentNegotiator::className(),
//                'formats' => [
//                    'application/json' => Response::FORMAT_JSON
//                ]
//            ],
//        ];
//    }
}

