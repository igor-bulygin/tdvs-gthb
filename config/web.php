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
		'languagepicker',
		'app\components\Aliases'
	],


	// TODO: Rename "api3" to "api", when all the Angular admin services are migrated
	'modules' => [
		'api3' => [
			'class' => 'app\modules\api\Module',
		],
	],

	'components' => [

		//Assets
		'assetManager' => [
			'appendTimestamp' => true,
			'bundles' => [
				'yii\bootstrap\BootstrapAsset' => [
					'sourcePath' => '',
					'css' => []
				],
			],
		],

		//Cache
		'cache' => [
			'class' => 'yii\redis\Cache',
			'redis' => require(__DIR__ . '/redis_cache.php'),
			'keyPrefix' => 'todevise_'
		],

		'devicedetect' => [
			'class' => 'alexandernst\devicedetect\DeviceDetect'
		],

		//Errors
		'errorHandler' => [
			'errorAction' => 'public/error'
		],

		//i18n
		'i18n' => [
			'translations' => [
				'app*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'fileMap' => [
						'app' => 'app.php',
						'app/admin' => 'app/admin.php',
						'app/deviser' => 'app/deviser.php',
						'app/public' => 'app/public.php'
					]
				]
			]
		],

		//Available languages
		'languagepicker' => [
			'class' => 'lajax\languagepicker\Component',
			'languages' => require(__DIR__ . '/langs.php'),
			'expireDays' => 64,
			'callback' => function () {
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
			'cookieValidationKey' => 'Yq$66191i>#VPkmnDgW<L@Ol<Sw4R+0A1?*9r49%.<02`Q:7_8^0)Pe#tp87',
			'parsers' => [
				'application/json' => 'yii\web\JsonParser',
			]
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
//            'enableStrictParsing' => true,
			'suffix' => '',
			'rules' => [
				// Test routing
				[
					'route' => 'public/test',
					'pattern' => 'test/<id:[^/.]*?>',
					'suffix' => ''
				],

				// Public routing
				'/' => 'public/index',
				'/index-new' => 'public/index',
				'/index-old' => 'public/index-old',
				'/category/<slug:[^/.]*?>/<category_id:[^/.]*?>' => 'public/category-b',
				'/work/<slug:[^/.]*?>/<product_id:[^/.]*?>' => 'product/detail',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/store' => 'deviser/store',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/about' => 'deviser/about',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/press' => 'deviser/press',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/video' => 'deviser/videos',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/faq' => 'deviser/faq',

				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/press/edit' => 'deviser/press-edit',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/video/edit' => 'deviser/videos-edit',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/faq/edit' => 'deviser/faq-edit',

				// Request become a Deviser
				'/become-a-deviser' => 'public/become-deviser',
				'/create-deviser-account' => 'public/create-deviser-account',
				'/create-deviser-account/<uuid:[^/.]*?>' => 'public/create-deviser-account',

				//Links for a cart listing
				'cart/' => 'public/cart',
				'public/cart/' => 'public/cart',

				//links for terms
				'terms/' => 'public/terms',

				//links for become a deviser
				'become/' => 'public/become',

				//Links for faq
				'faq/' => 'public/faq',

				//Links for contact
				'contact/' => 'public/contact',

				//Links for deviser profile
				'<slug:[0-9a-z-A-Z\-]*?>/products/' => 'admin/products',
				'<slug:[0-9a-z-A-Z\-]*?>/edit-info/' => 'deviser/edit-info',

				//Link for deviser header & profile photo upload
				'<slug:[0-9a-z-A-Z\-]*?>/upload-header-photo/' => 'deviser/upload-header-photo',
				'<slug:[0-9a-z-A-Z\-]*?>/upload-profile-photo/' => 'deviser/upload-profile-photo',
				'<slug:[0-9a-z-A-Z\-]*?>/<short_id:[0-9a-z-A-Z\-]*?>/upload-product-photo/' => 'deviser/upload-product-photo',
				'<slug:[0-9a-z-A-Z\-]*?>/<short_id:[0-9a-z-A-Z\-]*?>/delete-product-photo/' => 'deviser/delete-product-photo',

				//Link for deviser work
				'<slug:[0-9a-z-A-Z\-]*?>/edit-work/<short_id:\w{8}>/' => 'deviser/edit-work',

				//Link for deviser work photo upload
				'<slug:[0-9a-z-A-Z\-]*?>/upload-product-photo/<short_id:\w{8}>/' => 'deviser/upload-product-photo',

				//Links for admin
				'admin/faq/<faq_id:\w{5}>/' => 'admin/faq',
				'admin/faq/<faq_id:\w{5}>/<faq_subid:\w{1}>' => 'admin/faq',
				'admin/term/<term_id:\w{5}>/' => 'admin/term',
				'admin/term/<term_id:\w{5}>/<term_subid:\w{1}>' => 'admin/term',
				'admin/tag/<tag_id:\w{5}>/' => 'admin/tag',
				'admin/size-chart/<size_chart_id:\w{5}>/' => 'admin/size-chart',
				'admin/admin/<short_id:\w{7}>/' => 'admin/admin',
				'admin/<action:[^/.]*?>/' => 'admin/<action>',

				//Links for a category listing
				'<category_id:\w{5}>/<slug:[^/.]*?$>/' => 'public/category',
				'public/<category_id:\w{5}>/<' => 'public/tag',

				//Links for a product profile
				'<category_id:\w{5}>/<product_id:\w{8}>/<slug:[^/.]*?$>/' => 'public/product',
				'public/<category_id:\w{5}>/<product_id:\d{8}>/<slug:[^/.]*?$>/' => 'public/product',

				//Links for a product profile
				'<deviser_id:\w{7}>/<slug:[0-9a-z-A-Z\-]*?>/' => 'public/deviser',
				'public/<deviser_id:\w{7}>/<slug:[0-9a-z-A-Z\-]*?>/' => 'public/deviser',

				// API routing
				/* 'api/<action:[^/.]*?>/'  => 'api/<action>', */
				[
					'route' => 'api/<action>',
					'pattern' => 'api/<action:[^/.]*?>/',
					'suffix' => '/'
				],


				// API routing (public)
				'GET api3/pub/v1/products/<id:[^/.]*?>' => 'api3/pub/v1/product/view', // override "view" action to accept alphanumeric ids
				'POST api3/pub/v1/devisers/invitation-requests' => 'api3/pub/v1/deviser/invitation-requests-post',
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/product'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/faq'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/term'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/category'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/country'],

				// API routing (private)
				'GET api3/priv/v1/profile/deviser' => 'api3/priv/v1/deviser/view',
				'PATCH api3/priv/v1/profile/deviser' => 'api3/priv/v1/deviser/update',

				'GET api3/priv/v1/profile/user' => 'api3/priv/v1/user/view',
				'PATCH api3/priv/v1/profile/user' => 'api3/priv/v1/user/update',

				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/priv/v1/upload'],

				// API routing (admin)
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/admin/v1/invitation'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/admin/v1/faq'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/admin/v1/term'],
				'GET api3/pub/v1/languages' => 'api3/pub/v1/language/index',

			]
		],

		'user' => [
			'identityClass' => 'app\models\Person',
			'enableAutoLogin' => true,
			'enableSession' => true,
			'loginUrl' => ['/'],
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
		],
		'allowedIPs' => ['*']
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
