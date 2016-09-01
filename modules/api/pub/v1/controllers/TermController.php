<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use Yii;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\helpers\Utils;
use app\models\Person;
use app\models\Product;
use app\models\Country;
use app\models\SizeChart;
use yii\base\ActionFilter;
use yii\filters\VerbFilter;
use yii\filters\ContentNegotiator;

use app\models\Tag;
use app\models\Category;
use app\models\Faq;
use app\models\Term;

class TermController extends Controller {

    public function actionIndex()
    {
        // set the scenario to serialize objects
        Term::setSerializeScenario(Term::SERIALIZE_SCENARIO_PUBLIC);

        return Term::getSerialized();
    }
}
