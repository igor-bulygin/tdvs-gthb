<?php

use yii\filters\ContentNegotiator;
use yii\web\Response;

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
					//'sourcePath' => '',
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
//		'errorHandler' => [
//			'errorAction' => 'public/error'
//		],

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
					'levels' => YII_DEBUG ? ['error', 'warning', 'info'] : ['error', 'warning']
				]
			]
		],

		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => strpos($_SERVER['HTTP_HOST'], 'dev.todevise.com') === false && YII_ENV_DEV ? true : false,
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => '10.0.1.1',
				'username' => '',
				'password' => '',
				'port' => '25',
			],

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
				'/category/<slug:[^/.]*?>/<category_id:[^/.]*?>' => 'public/category-b',
				'/work/<slug:[^/.]*?>/<product_id:[^/.]*?>' => 'product/detail',

				// temporary routes to fix database problems
				'/works/fix-products' => 'product/fix-products',
				'/works/fix-products-with-no-deviser' => 'product/fix-products-with-no-deviser',
				'/works/more-works' => 'product/more-works',
				'/deviser/update-passwords' => 'deviser/update-passwords',

				'/works' => 'product/index',

				//Deviser
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>' => 'deviser/about',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/store' => 'deviser/store',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/about' => 'deviser/about',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/press' => 'deviser/press',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/video' => 'deviser/videos',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/faq' => 'deviser/faq',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/works/create' => 'product/create',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/works/<product_id:[^/.]*?>/edit' => 'product/edit',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/loved' => 'deviser/loved',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/boxes' => 'deviser/boxes',
				'/deviser/<slug:[^/.]*?>/<person_id:[^/.]*?>/box/<box_id:[^/.]*?>' => 'deviser/box-detail',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/store/edit' => 'deviser/store-edit',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/about/edit' => 'deviser/about-edit',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/press/edit' => 'deviser/press-edit',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/video/edit' => 'deviser/videos-edit',
				'/deviser/<slug:[^/.]*?>/<deviser_id:[^/.]*?>/faq/edit' => 'deviser/faq-edit',

				//Influencer
				'/influencer/<slug:[^/.]*?>/<person_id:[^/.]*?>' => 'influencer/about',
				'/influencer/<slug:[^/.]*?>/<person_id:[^/.]*?>/about' => 'influencer/about',
				'/influencer/<slug:[^/.]*?>/<person_id:[^/.]*?>/press' => 'influencer/press',
				'/influencer/<slug:[^/.]*?>/<person_id:[^/.]*?>/video' => 'influencer/videos',
				'/influencer/<slug:[^/.]*?>/<person_id:[^/.]*?>/loved' => 'influencer/loved',
				'/influencer/<slug:[^/.]*?>/<person_id:[^/.]*?>/boxes' => 'influencer/boxes',
				'/influencer/<slug:[^/.]*?>/<person_id:[^/.]*?>/box/<box_id:[^/.]*?>' => 'influencer/box-detail',
				'/influencer/<slug:[^/.]*?>/<person_id:[^/.]*?>/about/edit' => 'influencer/about-edit',
				'/influencer/<slug:[^/.]*?>/<person_id:[^/.]*?>/press/edit' => 'influencer/press-edit',
				'/influencer/<slug:[^/.]*?>/<person_id:[^/.]*?>/video/edit' => 'influencer/videos-edit',

				//Client
				'/client/<slug:[^/.]*?>/<person_id:[^/.]*?>/loved' => 'client/loved',
				'/client/<slug:[^/.]*?>/<person_id:[^/.]*?>/boxes' => 'client/boxes',
				'/client/<slug:[^/.]*?>/<person_id:[^/.]*?>/box/<box_id:[^/.]*?>' => 'client/box-detail',

				//Settings
				'/settings/<slug:[^/.]*?>/<person_id:[^/.]*?>' => 'settings/index',
				'/settings/<slug:[^/.]*?>/<person_id:[^/.]*?>/billing' => 'settings/billing',
				'/settings/<slug:[^/.]*?>/<person_id:[^/.]*?>/connect-stripe' => 'settings/connect-stripe',
				'/settings/<slug:[^/.]*?>/<person_id:[^/.]*?>/disconnect-stripe' => 'settings/disconnect-stripe',

				//Orders
				'order/success/<order_id:\w{8}>' => 'order/success',

				//Stripe
				'stripe/connect-back' => 'stripe/connect-back',

				//Links for contact
				'login' => 'site/login',

				// Request become a Deviser
				'/become-a-deviser' => 'public/become-deviser',
				'/create-deviser-account' => 'public/create-deviser-account',
				'/create-influencer-account' => 'public/create-influencer-account',
				'/signup' => 'public/signup',

				//Links for a cart listing
				'cart/' => 'public/cart',

				//links for terms
				'terms/' => 'public/terms',

				//Links for faq
				'faq/' => 'public/faq',

				//Links for contact
				'about-us' => 'public/about-us',

				// Postman (temp urls)
//				'/postman/emails' => 'postman/index',
				'/postman/emails/<uuid:[^/.]*?>' => 'postman/email-view',

				// Routes to show mockups to product owner
				'/postman/mockups/deviser-request-invitation' => 'postman/mockup-deviser-request-invitation-view',
				'/postman/mockups/deviser-invitation' => 'postman/mockup-deviser-invitation-view',

				//Links for admin
				'admin/faq/<faq_id:\w{5}>/' => 'admin/faq',
				'admin/faq/<faq_id:\w{5}>/<faq_subid:\w{1}>' => 'admin/faq',
				'admin/term/<term_id:\w{5}>/' => 'admin/term',
				'admin/term/<term_id:\w{5}>/<term_subid:\w{1}>' => 'admin/term',
				'admin/tag/<tag_id:\w{5}>/' => 'admin/tag',
				'admin/size-chart/<size_chart_id:\w{5}>/' => 'admin/size-chart',
				'admin/admin/<short_id:\w{7}>/' => 'admin/admin',
				'admin/<action:[^/.]*?>/' => 'admin/<action>',

				/********************************** @deprecated routes ************************************************/

				// Public routing
				'/index-old' => 'public/index-old',

				//Links for a category listing
				'<category_id:\w{5}>/<slug:[^/.]*?$>/' => 'public/category',

				//Links for a product profile
				'<category_id:\w{5}>/<product_id:\w{8}>/<slug:[^/.]*?$>/' => 'public/product',
				'public/<category_id:\w{5}>/<product_id:\d{8}>/<slug:[^/.]*?$>/' => 'public/product',

				//Links for a product profile
				'<deviser_id:\w{7}>/<slug:[0-9a-z-A-Z\-]*?>/' => 'public/deviser',
				'public/<deviser_id:\w{7}>/<slug:[0-9a-z-A-Z\-]*?>/' => 'public/deviser',

				//Links for a cart listing
				'public/cart/' => 'public/cart-old',

				//links for become a deviser
				'become/' => 'public/become',

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

				/******************************************************************************************************/


				// API routing
				/* 'api/<action:[^/.]*?>/'  => 'api/<action>', */
				[
					'route' => 'api/<action>',
					'pattern' => 'api/<action:[^/.]*?>/',
					'suffix' => '/'
				],


				// API routing (public)
				'GET api3/pub/v1/products/<id:[^/.]*?>' => 'api3/pub/v1/product/view', // override "view" action to accept alphanumeric ids
				'POST api3/pub/v1/invitation/request-become-deviser' => 'api3/pub/v1/invitation/request-become-deviser',

				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/product'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/faq'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/term'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/category'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/country'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/location'],
				'GET api3/pub/v1/invitations/<uuid:[^/.]*?>' => 'api3/pub/v1/invitation/view', // override "view" action to accept alphanumeric ids

				'POST api3/pub/v1/auth/login' => 'api3/pub/v1/auth/login',

				// CART
				'POST api3/pub/v1/cart' => 'api3/pub/v1/cart/create-cart',
				'GET api3/pub/v1/cart/<cartId:[^/.]*?>' => 'api3/pub/v1/cart/view',
				'POST api3/pub/v1/cart/<cartId:[^/.]*?>/product' => 'api3/pub/v1/cart/add-product',
				'PUT api3/pub/v1/cart/<cartId:[^/.]*?>/product/<priceStockId:[^/.]*?>' => 'api3/pub/v1/cart/update-product',
				'DELETE api3/pub/v1/cart/<cartId:[^/.]*?>/product/<priceStockId:[^/.]*?>' => 'api3/pub/v1/cart/delete-product',
				'POST api3/pub/v1/cart/<cartId:[^/.]*?>/clientInfo' => 'api3/pub/v1/cart/client-info',
				'POST api3/pub/v1/cart/<cartId:[^/.]*?>/receiveToken' => 'api3/pub/v1/cart/receive-token',

				// ORDER
				'GET api3/pub/v1/order/<orderId:[^/.]*?>' => 'api3/pub/v1/order/view',

				// PERSON
				'POST api3/pub/v1/person' => 'api3/pub/v1/person/create',
				'POST api3/pub/v1/person/signup' => 'api3/pub/v1/person/signup',
				'GET api3/priv/v1/person/<personId:[^/.]*?>' => 'api3/priv/v1/person/view',
				'PATCH api3/priv/v1/person/<personId:[^/.]*?>' => 'api3/priv/v1/person/update',
				'PUT api3/priv/v1/person/<personId:[^/.]*?>' => 'api3/priv/v1/person/update',

				// PRODUCT
				'GET api3/priv/v1/products/<id:[^/.]*?>' => 'api3/priv/v1/product/view',
				'GET api3/priv/v1/products' => 'api3/priv/v1/product/index',
				'POST api3/priv/v1/products' => 'api3/priv/v1/product/create',
				'PATCH api3/priv/v1/products/<id:[^/.]*?>' => 'api3/priv/v1/product/update',
				'PUT api3/priv/v1/products/<id:[^/.]*?>' => 'api3/priv/v1/product/update',
				'DELETE api3/priv/v1/products/<id:[^/.]*?>' => 'api3/priv/v1/product/delete',

				'GET api3/pub/v1/loved/<lovedId:[^/.]*?>' => 'api3/pub/v1/loved/view',
				'GET api3/pub/v1/loved' => 'api3/pub/v1/loved/index',

				'GET api3/priv/v1/loved/<lovedId:[^/.]*?>' => 'api3/priv/v1/loved/view',
				'GET api3/priv/v1/loved' => 'api3/priv/v1/loved/index',
				'POST api3/priv/v1/loved' => 'api3/priv/v1/loved/create',
				'DELETE api3/priv/v1/loved/<productId:[^/.]*?>' => 'api3/priv/v1/loved/delete',


				'GET api3/pub/v1/box/<boxId:[^/.]*?>' => 'api3/pub/v1/box/view',
				'GET api3/pub/v1/box' => 'api3/pub/v1/box/index',

				'GET api3/priv/v1/box' => 'api3/priv/v1/box/index',
				'GET api3/priv/v1/box/<boxId:[^/.]*?>' => 'api3/priv/v1/box/view',
				'POST api3/priv/v1/box' => 'api3/priv/v1/box/create',
				'PATCH api3/priv/v1/box/<boxId:[^/.]*?>' => 'api3/priv/v1/box/update',
				'DELETE api3/priv/v1/box/<boxId:[^/.]*?>' => 'api3/priv/v1/box/delete',
				'POST api3/priv/v1/box/<boxId:[^/.]*?>/product' => 'api3/priv/v1/box/add-product',
				'DELETE api3/priv/v1/box/<boxId:[^/.]*?>/product/<productId:[^/.]*?>' => 'api3/priv/v1/box/delete-product',

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
			'loginUrl' => ['/login'],
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
