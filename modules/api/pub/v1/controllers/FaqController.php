<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Faq;
use Yii;

class FaqController extends AppPublicController {

    public function actionIndex()
    {
        // set the scenario to serialize objects
        Faq::setSerializeScenario(Faq::SERIALIZE_SCENARIO_PUBLIC);

        return Faq::getSerialized();
    }

}

