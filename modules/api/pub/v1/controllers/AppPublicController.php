<?php

namespace app\modules\api\pub\v1\controllers;

use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\HttpException;

class AppPublicController extends Controller
{
	protected $requestIdentifier = null;

	public function init()
	{
		parent::init();

		$this->requestIdentifier = \Yii::$app->security->generateRandomString();

		\Yii::$app->user->enableSession = false;   // restfull must be stateless => no session
		\Yii::$app->user->loginUrl = null;         // force 403 response
	}

	public function behaviors()
	{
		$behaviors = parent::behaviors();

		$behaviors['authenticator'] = [
			'class' => HttpBearerAuth::className(),
			'optional' => ['*'], // in public api, BearerAuth is optional in all the actions
		];
		$behaviors['access'] = [
				'class' => AccessControl::className(),
				'rules' => [
						[
								'allow' => true,
								'roles' => ['?', '@'] //? guest, @ authenticated
						]
				],
		];

		return $behaviors;
	}

	public function runAction($id, $params = [])
	{
		try {
			return parent::runAction($id, $params);
		} catch (HttpException $e) {

			$message =
				"\nPUBLIC API ACTION ".$this->requestIdentifier.": ".
				"\n - exception => ".$e->getMessage().
				"\n - status_code => " . $e->statusCode.
				"\n";
			\Yii::info($message, __METHOD__);

			throw $e;
		} catch (\Exception $e) {

			$message =
				"\nPUBLIC API ACTION ".$this->requestIdentifier.": ".
				"\n - exception => ".$e->getMessage().
				"\n - status_code => 500".
				"\n";
			\Yii::info($message, __METHOD__);

			if (YII_DEBUG) {
				throw $e;
			}
			throw new HttpException(500, $e->getMessage());
		}
	}

	public function beforeAction($action)
	{
		$message =
			"\nPUBLIC API ACTION ".$this->requestIdentifier.": ".
			"\n - url => " . \Yii::$app->request->method." ". Url::current() .
			"\n - http_authorization => " . (isset($_SERVER["HTTP_AUTHORIZATION"]) ? $_SERVER["HTTP_AUTHORIZATION"] : "") .
			"\n - body_params => " . \Yii::$app->request->rawBody.
			"\n";
		\Yii::info($message, __METHOD__);

		return parent::beforeAction($action);
	}

	public function afterAction($action, $result)
	{
		$message =
			"\nPUBLIC API ACTION ".$this->requestIdentifier.": ".
			"\n - status_code => " . \Yii::$app->response->statusCode.
			"\n";
		\Yii::info($message, __METHOD__);

		return parent::afterAction($action, $result);
	}

}