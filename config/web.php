<?php

use yii\web\Response;
use yii\filters\ContentNegotiator;

$config = [
	'id' => 'basic',

	'basePath' => dirname(__DIR__),

	'bootstrap' => [
		//Be very careful, the order of those elements is important!
		'log',
		[
			'class' => ContentNegotiator::className(),
			'formats' => [
				'text/html' => Response::FORMAT_HTML,
				'application/xhtml+xml' => Response::FORMAT_HTML,
				'application/json' => Response::FORMAT_JSON,
				'application/xml' => Response::FORMAT_XML
			],
			'languages' => array_keys(require(__DIR__ . '/langs.php'))
		],
		'devicedetect',
		'languagepicker'
	],

	'components' => [

		//Assets
		'assetManager' => [
			'appendTimestamp' => true
		],

		//Cache
		'cache' => [
			'class' => 'yii\redis\Cache',
			'redis' => require(__DIR__ . '/redis_cache.php')
		],

		'devicedetect' => [
			'class' => 'alexandernst\devicedetect\DeviceDetect'
		],

		//Errors
		'errorHandler' => [
			'errorAction' => 'site/error'
		],

		//Available languages
		'languagepicker' => [
			'class' => 'lajax\languagepicker\Component',
			'languages' => require(__DIR__ . '/langs.php'),
			'expireDays' => 64,
			'callback' => function() {
				if (!\Yii::$app->user->isGuest) {
					/* @var $person \app\models\Person */
					$person = \Yii::$app->user->identity;
					$person->setLanguage(\Yii::$app->language);
					$person->save();
				}
			}
		],

		//Log
		'log' => [
			'traceLevel' => YII_DEBUG ? 3 : 0,
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning']
				]
			]
		],

		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => true
		],

		//Database
		'mongodb' => require(__DIR__ . '/db.php'),

		//Redis
		'redis' => require(__DIR__ . '/redis.php'),

		//Requests and cookies
		'request' => [
			'cookieValidationKey' => 'Yq$66191i>#VPkmnDgW<L@Ol<Sw4R+0A1?*9r49%.<02`Q:7_8^0)Pe#tp87'
		],

		//Sessions
		'session' => [
			'class' => 'yii\redis\Session',
			'redis' => require(__DIR__ . '/redis_session.php')
		],

		'Scrypt' => [
			'class' => 'alexandernst\Scrypt\Scrypt'
		],

		//URLs
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
			'suffix' => '/',
			'rules' => [
				//Links for admin
				'admin/<action:[^/.]*?>/'  => 'admin/<action>',

				//Links for a category listing
				'<category_id:\w{5}>/<slug:[^/.]*?$>/' => 'public/category',
				'public/<category_id:\w{5}>/<slug:[^/.]*?$>/' => 'public/category',

				//Links for a product profile
				'<category_id:\w{5}>/<product_id:\w{7}>/<slug:[^/.]*?$>/' => 'public/product',
				'public/<category_id:\w{5}>/<product_id:\d{7}>/<slug:[^/.]*?$>/' => 'public/product'
			]
		],

		'user' => [
			'identityClass' => 'app\models\Person',
			'enableAutoLogin' => true
		]

	],

	'defaultRoute' => 'public',

	'params' => require(__DIR__ . '/params.php')
];

if (YII_ENV_DEV) {
	// configuration adjustments for 'dev' environment
	$config['bootstrap'][] = 'debug';
	$config['modules']['debug'] = [
		'class' => 'yii\debug\Module',
		'panels' => [
			'views' => [
				'class' => 'yii\mongodb\debug\MongoDbPanel'
			]
		]
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
