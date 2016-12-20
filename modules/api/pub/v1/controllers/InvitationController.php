<?php

namespace app\modules\api\pub\v1\controllers;

use app\models\Invitation;
use Yii;
use yii\web\NotFoundHttpException;

class InvitationController extends AppPublicController {

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

