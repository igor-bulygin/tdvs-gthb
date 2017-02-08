<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Person;
use app\modules\api\priv\v1\forms\UploadForm;
use Yii;
use yii\base\Model;
use yii\web\BadRequestHttpException;
use yii\web\UploadedFile;

class UploadController extends AppPrivateController
{

	/**
	 * Process new users upload files
	 * It's generic. In form must be specified the use of the uploaded file, for example, an avatar, press image, etc.
	 * @return array|Model
	 * @throws BadRequestHttpException
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

