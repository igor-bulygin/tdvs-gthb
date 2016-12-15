<?php

namespace app\controllers;

use app\models\ContactForm;
use app\models\Login;
use Yii;
use yii\filters\AccessControl;
use yii\filters\VerbFilter;
use yii\web\Controller;

class SiteController extends Controller
{
	public function behaviors()
	{
		return [
			'access' => [
				'class' => AccessControl::className(),
				'only' => ['login', 'logout'],
				'rules' => [
						[
								'actions' => ['login'],
								'allow' => true,
								'roles' => ['?'],
						],
						[
								'actions' => ['login'],
								'allow' => false,
								'roles' => ['@'],
								'denyCallback' => function ($rule, $action) {
									return $this->goHome();
								}
						],
					[
						'actions' => ['logout'],
						'allow' => true,
						'roles' => ['@'],
					],
				],
			],
			'verbs' => [
				'class' => VerbFilter::className(),
				'actions' => [
					'logout' => ['post'],
				],
			],
		];
	}

	public function actions()
	{
		return [
			/*
			'error' => [
				'class' => 'yii\web\ErrorAction',
			],
			*/
			'captcha' => [
				'class' => 'yii\captcha\CaptchaAction',
				'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
			],
		];
	}

	public function actionIndex()
	{
		return $this->render('index');
	}

	public function actionLogin()
	{
		$model = new Login();
		$invalidLogin = false;
		if ($model->load(Yii::$app->request->post())) {
			if ($model->login()) {
				return $this->goBack();
			}
			$invalidLogin = true;
		}
		$this->layout = '/desktop/public-2.php';
		return $this->render("login-2", [
			'invalidLogin' => $invalidLogin
		]);
	}

	public function actionLogout()
	{
		Yii::$app->user->logout();

		return $this->goHome();
	}

	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['adminEmail'])) {
			Yii::$app->session->setFlash('contactFormSubmitted');

			return $this->refresh();
		} else {
			return $this->render('contact', [
				'model' => $model,
			]);
		}
	}

	public function actionAbout()
	{
		return $this->render('about');
	}
}
