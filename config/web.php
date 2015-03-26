<?php

$config = [
	'id' => 'basic',

	'basePath' => dirname(__DIR__),

	'bootstrap' => ['log', 'languagepicker'],

	'components' => [

		//Requests and cookies
		'request' => [
			'cookieValidationKey' => 'Yq$66191i>#VPkmnDgW<L@Ol<Sw4R+0A1?*9r49%.<02`Q:7_8^0)Pe#tp87',
		],

		//Cache
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],

		//Assets
		'assetManager' => [
			'appendTimestamp' => true,
		],

		//Log
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],

		//Errors
		'errorHandler' => [
			'errorAction' => 'site/error',
		],

		//Database
		'mongodb' => require(__DIR__ . '/db.php'),

		//Sessions
		'session' => [
			'class' => 'yii\mongodb\Session',
			'sessionCollection' => 'tmp_sessions'
		],

		//URLs
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'rules' => [
			]
		],

		'devicedetect' => [
			'class' => 'alexandernst\devicedetect\DeviceDetect'
		],

		//Available languages
		'languagepicker' => [
			'class' => 'lajax\languagepicker\Component',
			'languages' => require(__DIR__ . '/langs.php'),
			'cookieName' => 'lng',
			'expireDays' => 64,
			'callback' => function() {
				/*
				if (!\Yii::$app->user->isGuest) {
					$user = \Yii::$app->user->identity;
					$user->language = \Yii::$app->language;
					$user->save();
				}
				*/
			}
		],

		'user' => [
			'identityClass' => 'app\models\User',
			'enableAutoLogin' => true,
		],

		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => true,
		],
	],

	'defaultRoute' => 'public',

	'params' => require(__DIR__ . '/params.php')
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = 'yii\debug\Module';

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
