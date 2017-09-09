<?php

namespace app\modules\api\priv\v1\controllers;

use app\models\Person;
use Yii;
use yii\filters\AccessControl;
use yii\filters\auth\HttpBearerAuth;
use yii\helpers\Url;
use yii\rest\Controller;
use yii\web\BadRequestHttpException;
use yii\web\HttpException;

class AppPrivateController extends Controller
{
	protected $requestIdentifier = null;

	public function init()
	{
		parent::init();

		$this->requestIdentifier = \Yii::$app->security->generateRandomString();

		\Yii::$app->user->enableSession = false; // restfull must be stateless => no session
		\Yii::$app->user->loginUrl = null; 		 // force 403 response
	}

	public function behaviors()
	{

		$behaviors = parent::behaviors();

		$behaviors['authenticator'] = [
				'class' => HttpBearerAuth::className(),
		];
		$behaviors['access'] = [
				'class' => AccessControl::className(),
				'rules' => [
						[
								'allow' => true,
								'roles' => ['@'] //? guest, @ authenticated
						]
				]
		];

		return $behaviors;
	}

	public function runAction($id, $params = [])
	{
		try {
			return parent::runAction($id, $params);
		} catch (HttpException $e) {
			throw $e;
		} catch (\Exception $e) {
			throw new HttpException(500, $e->getMessage());
		}
	}

	public function beforeAction($action)
	{
		$message =
			"\nPRIVATE API ACTION ".$this->requestIdentifier.": ".
			"\n - url => " . Url::current() .
			"\n - http_authorization => " . (isset($_SERVER["HTTP_AUTHORIZATION"]) ? $_SERVER["HTTP_AUTHORIZATION"] : "") .
			"\n - body_params => " . \Yii::$app->request->rawBody.
			"\n";
		\Yii::info($message, __METHOD__);

		return parent::beforeAction($action);
	}

	public function afterAction($action, $result)
	{
		$message =
			"\nPRIVATE API ACTION ".$this->requestIdentifier.": ".
			"\n - status_code => " . \Yii::$app->response->statusCode.
			"\n";
		\Yii::info($message, __METHOD__);

		return parent::afterAction($action, $result);
	}

	/**
	 * @return Person
	 * @throws BadRequestHttpException
	 */
	protected function getPerson()
	{
		$person_id = Yii::$app->request->isGet ? Yii::$app->request->get("person_id") : Yii::$app->request->post("person_id");
		if (empty($person_id)) {
			// If person_id is not specified, we try to find a deviser_id
			$person_id = Yii::$app->request->isGet ? Yii::$app->request->get("deviser_id") : Yii::$app->request->post("deviser_id");
		}
		if (empty($person_id)) {
			throw new BadRequestHttpException('Person id not specified');
		}
		/** @var Person $person */
		$person = Person::findOne(["short_id" => $person_id]);
		if (empty($person)) {
			throw new BadRequestHttpException('Person not found');
		}

		return $person;

	}

}

