<?php

namespace app\modules\api\pub\v1\controllers;

use yii\web\BadRequestHttpException;
use yii\web\NotFoundHttpException;

class DeviserController extends AppPublicController
{

	/**
	 * Create a new Deviser account
	 *
	 * @throws BadRequestHttpException
	 */
	public function actionCreate()
	{
		throw new NotFoundHttpException("This method was moved to POST /api/pub/v1/person");
	}

	/**
	 * Process the request of new Devisers to join the platform
	 *
	 */
	public function actionInvitationRequestsPost()
	{
		throw new NotFoundHttpException("This method was moved to POST /api/pub/v1/invitation/request-become-deviser");
	}

}