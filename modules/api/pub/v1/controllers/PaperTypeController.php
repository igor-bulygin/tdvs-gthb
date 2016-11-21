<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\PaperType;
use Yii;
use yii\rest\Controller;


class PaperTypeController extends Controller {

    public function actionIndex()
	{
		return PaperType::getSerialized();
	}
}

