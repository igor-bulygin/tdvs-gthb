<?php

namespace app\controllers;

use app\models\ContactForm;
use Yii;
use yii\filters\VerbFilter;
use yii\web\Controller;

class SiteController extends Controller
{
	public function behaviors()
	{
		return [
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

	/**
	 * @deprecated
	 * @return string
	 */
	public function actionIndex()
	{
		return $this->render('_index');
	}

	/**
	 * @deprecated
	 * @return string|\yii\web\Response
	 */
	public function actionContact()
	{
		$model = new ContactForm();
		if ($model->load(Yii::$app->request->post()) && $model->contact(Yii::$app->params['admin_email'])) {
			Yii::$app->session->setFlash('contactFormSubmitted');

			return $this->refresh();
		} else {
			return $this->render('_contact', [
				'model' => $model,
			]);
		}
	}
}
