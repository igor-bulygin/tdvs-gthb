<?php

namespace app\modules\api\admin\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Invitation;
use app\modules\api\admin\v1\forms\InvitationForm;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class InvitationController extends Controller {

//    public $modelClass = 'app\models\Term';

    public function actionIndex()
    {
        // set the scenario to serialize objects
        Invitation::setSerializeScenario(Faq::SERIALIZE_SCENARIO_ADMIN);

	    // set pagination values
	    $limit = Yii::$app->request->get('limit', 20);
	    $limit = ($limit < 1) ? 1 : $limit;
	    // not allow more than 100 products for request
	    $limit = ($limit > 100) ? 100 : $limit;
	    $page = Yii::$app->request->get('page', 1);
	    $page = ($page < 1) ? 1 : $page;
	    $offset = ($limit * ($page - 1));


        return Invitation::findSerialized([
	        "limit" => $limit,
	        "offset" => $offset,
        ]);
    }

    public function actionView($id)
    {
        return ["action" => "view", "id" => $id];
    }

    public function actionCreate()
    {
	    $invitation = new Invitation();
	    if ($invitation->load(Yii::$app->request->post(), '') && $invitation->validate()) {
	    	// send the invitation

		    Yii::$app->response->setStatusCode(201); // Created
		    return ["action" => "created"];
	    } else {
		    Yii::$app->response->setStatusCode(400); // Bad Request
		    return ["errors" => $invitation->errors];
	    }

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
}

