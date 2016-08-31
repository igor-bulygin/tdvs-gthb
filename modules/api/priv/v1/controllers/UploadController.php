<?php

namespace app\modules\api\priv\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Person;
use app\modules\api\priv\v1\forms\UploadForm;
use Yii;
use yii\base\Model;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;
use yii\web\UploadedFile;
use yii\web\User;

class UploadController extends Controller {

    public function init() {
        parent::init();

        // TODO: retrieve current identity from one of the available authentication methods in Yii
        Yii::$app->user->login(Person::findOne(["short_id" => "13cc33k"]));
    }

	/**
	 * Process new users upload files
	 * It's generic. In form must be specified the use of the uploaded file, for example, an avatar, press image, etc.
	 *
	 * @return array|Model
	 */
    public function actionCreate()
    {
	    /** @var Person $deviser */
	    $deviser = Yii::$app->user->getIdentity();

	    $uploadForm = new UploadForm();
	    $uploadForm->load(Yii::$app->request->post(), '');
//	    $uploadForm->type = UploadForm::UPLOAD_TYPE_DEVISER_PRESS_IMAGES;
	    // force to relate images to logged user
	    $uploadForm->deviser_id = $deviser->short_id;
	    $uploadForm->file = UploadedFile::getInstanceByName("file");
	    if ($uploadForm->upload()) {
		    // file is uploaded successfully
		    // return information needed to client side
		    return $uploadForm;
	    } else {
		    Yii::$app->response->setStatusCode(400); // Bad Request
		    return ["errors" => $uploadForm->errors];
	    }
    }
}

