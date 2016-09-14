<?php

namespace app\modules\api\pub\v1\controllers;

use app\helpers\CActiveRecord;
use app\models\Invitation;
use app\models\Product;
use Yii;
use yii\mongodb\ActiveQuery;
use yii\rest\Controller;
use yii\web\ForbiddenHttpException;
use yii\web\NotFoundHttpException;
use yii\web\Response;
use app\helpers\Utils;
use yii\filters\ContentNegotiator;

use app\models\Faq;

class InvitationController extends Controller {

	public function actionView($uuid)
	{
		// show only fields needed in this scenario
		Invitation::setSerializeScenario(Invitation::SERIALIZE_SCENARIO_PUBLIC);

		/** @var Invitation $invitation */
		$invitation = Invitation::findOneSerialized($uuid);
		if (!$invitation) {
			throw new NotFoundHttpException("Invitation not found");
		}

		return $invitation;
	}

}

