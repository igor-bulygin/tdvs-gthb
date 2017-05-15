<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Currency;


class CurrencyController extends AppPublicController {

    public function actionIndex()
    {
		return Currency::getSerialized();
    }
}

