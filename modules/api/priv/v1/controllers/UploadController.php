<?php

namespace app\modules\api\priv\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Person;
use app\modules\api\priv\v1\forms\UploadForm;
use Yii;
use yii\base\Model;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\ForbiddenHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;
use yii\web\UploadedFile;
use yii\web\User;

class UploadController extends AppPrivateController
{

	/**
	 * Process new users upload files
	 * It's generic. In form must be specified the use of the uploaded file, for example, an avatar, press image, etc.
	 *
	 * @return array|Model
	 */
	public function actionCreate()
	{
		/** @var Person $deviser */
		$deviser = $this->getPerson();

		$uploadForm = new UploadForm();
		$uploadForm->load(Yii::$app->request->post(), '');
		$uploadForm->setScenarioByUploadType();
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

