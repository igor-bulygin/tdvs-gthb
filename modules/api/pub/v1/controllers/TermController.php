<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Term;
use Yii;

class TermController extends AppPublicController {

    public function actionIndex()
    {
        // set the scenario to serialize objects
        Term::setSerializeScenario(Term::SERIALIZE_SCENARIO_PUBLIC);

        return Term::getSerialized();
    }
}
