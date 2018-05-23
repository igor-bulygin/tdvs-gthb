<?php

use yii\filters\ContentNegotiator;
use yii\web\Response;

$config = [
	'id' => 'basic',
	'name' => 'Todevise',

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
			'languages' => array_keys(require(__DIR__ . '/langs.php')), // must be an array of language codes
		],
		'devicedetect',
		'app\components\Aliases',
		'languagepicker',
//		'newrelic',
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
			'bundles' =>
				false //YII_ENV_PROD
				?
					(require __DIR__ . '/assets_compressed.php')
				:
					[
						'yii\bootstrap\BootstrapAsset' => [
							//'sourcePath' => '',
							'css' => []
						],
					]
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
			'errorAction' => YII_ENV_PROD ? 'public/error' : null,
		],

		//i18n
		'i18n' => [
			'translations' => [
				'app*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'fileMap' => [
						'app' => 'app.php',
						'app/admin' => 'app/admin.php',
						'app/public' => 'app/public.php',
					],
					'forceTranslation' => true,
				]
			],
		],

		//Available languages
		'languagepicker' => [
			'class' => 'lajax\languagepicker\Component',
			'languages' => [ // must be an key => value array of language code => name
				'es-ES' => 'ES',
				'en-US' => 'EN',
			],
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
			'flushInterval' => 100,
			'traceLevel' => YII_DEBUG ? 0 : 0,
			'targets' => [
				'todevise_db_error' =>[
					'class' => 'yii\log\EmailTarget',
					'levels' => ['error', 'warning'],
					'categories' => ['yii\db\*', 'yii\mongodb\*'],
					'message' => [
						'from' => ['info@todevise.com'],
						'to' => ['josevazquezviader@gmail.com'],
						'subject' => 'TODEVISE - Database error',
					],
				],
				'todevise_log' => [
					'class' => 'yii\mongodb\log\MongoDbTarget',
					'levels' => ['error', 'warning'],
					'except' => ['yii\web\HttpException:401', 'yii\web\HttpException:404'],
					'logCollection' => 'todeviselog',
					'logVars' => [],
				],
				'todevise_errors' =>[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
					'except' => ['yii\web\HttpException:401', 'yii\web\HttpException:404'],
					'logFile' => '@app/runtime/logs/todevise_errors.log',
					'logVars' => [],
				],
				'todevise_errors_detail' =>[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
					'except' => ['yii\web\HttpException:401', 'yii\web\HttpException:404'],
					'logFile' => '@app/runtime/logs/todevise_errors_detail.log',
					'logVars' => ['_GET', '_POST', '_FILES', '_COOKIE', '_SESSION', '_SERVER'],
				],
				'todevise_api' => [
					'class' => 'yii\log\FileTarget',
					'levels' => ['info'],
					'categories' => ['app\modules\api\*'],
					'logFile' => '@app/runtime/logs/todevise_api.log',
					'logVars' => [],
				],
				'todevise_api_errors' => [
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
					'categories' => ['app\modules\api\*'],
					'logFile' => '@app/runtime/logs/todevise_api_errors.log',
					'logVars' => [],
				],
				'todevise_stripe' => [
					'class' => 'yii\log\FileTarget',
					'levels' => ['info'],
					'categories' => ['app\controllers\Stripe*', 'app\helpers\Stripe*', 'Stripe'],
					'logFile' => '@app/runtime/logs/todevise_stripe.log',
					'logVars' => ['_GET', '_POST'],
				],
			],
		],

		'mailer' => [
			'class' => 'yii\swiftmailer\Mailer',
			// send all mails to a file by default. You have to set
			// 'useFileTransport' to false and configure a transport
			// for the mailer to send real emails.
			'useFileTransport' => YII_ENV_PROD ? false : true,
			'transport' => [
				'class' => 'Swift_SmtpTransport',
				'host' => 'localhost',
				'username' => '',
				'password' => '',
				'port' => '25',
			],

		],

		//Database
		'mongodb' => require(__DIR__ . '/db.php'),

		//OpenGraph
		'opengraph' => [
			'class' => 'dragonjet\opengraph\OpenGraph',
			'title' => 'Todevise',
			'site_name' => 'Todevise',
			'type' => 'website',
			'image' => 'https://todevise.com/logo.png',
		],

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

		// new relic
//		'newrelic' => [
//			'class' => 'bazilio\yii\newrelic\Newrelic',
//			'name' => 'Dev Todevise',
////			'handler' => 'class/name',
//			'enabled' =>
//				// newrelic is only enabled ind real servers
//				strpos($_SERVER['HTTP_HOST'], 'beta.todevise.com') !== false ||
//				strpos($_SERVER['HTTP_HOST'], 'dev.todevise.com') !== false,
//		],

		//URLs
		'urlManager' => [
			'enablePrettyUrl' => true,
			'showScriptName' => false,
//            'enableStrictParsing' => true,
			'suffix' => '',
			'rules' => [

				// temporary routes to fix database problems
				'/works/fix-products' => 'product/fix-products',
				'/works/fix-products-with-no-deviser' => 'product/fix-products-with-no-deviser',
				'/works/more-works' => 'product/more-works',


				// Public routing
				'/' => 'public/index',
				'/index-new' => 'public/index',
				'/public/more-works' => 'public/more-works',

				'/works/<slug:[^/.]*?>/<category_id:[^/.]*?>' => 'public/category-b',
				'/works' => 'product/index',

				'/admin/reset-password/<person_id:[^/.]*?>' => 'admin/reset-password',
				'/admin/invoices-excel/<date_from:[^/.]*?>/<date_to:[^/.]*?>' => 'admin/invoices-excel',
				'/admin/packages-excel/<date_from:[^/.]*?>/<date_to:[^/.]*?>/<deviser_id:[^/.]*?>' => 'admin/packages-excel',
				'/admin/mandrill-content/<message_id:[^/.]*?>' => 'admin/mandrill-content',

				//Person
				'/<person_type:(deviser|influencer|client)>/<slug:[^/.]*?>/<person_id:[^/.]*?>' => 'person/index',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/complete-profile' => 'person/complete-profile',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/person-not-public' => 'person/person-not-public',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/about' => 'person/about',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/press' => 'person/press',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/video' => 'person/videos',

				'/<person_type:(deviser|influencer|client)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/loved' => 'person/loved',
				'/<person_type:(deviser|influencer|client)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/boxes' => 'person/boxes',
				'/<person_type:(deviser|influencer|client)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/box/<box_id:[^/.]*?>' => 'person/box-detail',

				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/social' => 'person/social',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/connect-instagram' => 'person/connect-instagram',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/follow' => 'person/follow',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/followers' => 'person/followers',

				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/stories' => 'person/stories',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/stories/create' => 'person/story-create',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/story/<story_id:[^/.]*?>/edit' => 'person/story-edit',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/story/<story_id:[^/.]*?>/<slug_story:[^/.]*?>' => 'person/story-detail',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/about/edit' => 'person/about-edit',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/press/edit' => 'person/press-edit',
				'/<person_type:(deviser|influencer)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/video/edit' => 'person/videos-edit',
				'/<person_type:(deviser)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/faq' => 'person/faq',
				'/<person_type:(deviser)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/faq/edit' => 'person/faq-edit',
				'/<person_type:(deviser)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/store' => 'person/store',
				'/<person_type:(deviser)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/store/edit' => 'person/store-edit',

				'/<person_type:(deviser)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/works/create' => 'product/create',
				'/<person_type:(deviser)>/<slug:[^/.]*?>/<person_id:[^/.]*?>/works/<product_id:[^/.]*?>/edit' => 'product/edit',

				'/work/<slug:[^/.]*?>/<product_id:[^/.]*?>' => 'product/detail',

				//Settings
				'/settings/<slug:[^/.]*?>/<person_id:[^/.]*?>' => 'settings',
				'/settings/<slug:[^/.]*?>/<person_id:[^/.]*?>/general' => 'settings/general',
				'/settings/<slug:[^/.]*?>/<person_id:[^/.]*?>/billing' => 'settings/billing',
				'/settings/<slug:[^/.]*?>/<person_id:[^/.]*?>/shipping' => 'settings/shipping',
				'/settings/<slug:[^/.]*?>/<person_id:[^/.]*?>/connect-stripe' => 'settings/connect-stripe',
				'/settings/<slug:[^/.]*?>/<person_id:[^/.]*?>/disconnect-stripe' => 'settings/disconnect-stripe',
				'/settings/<slug:[^/.]*?>/<person_id:[^/.]*?>/open-orders' => 'settings/open-orders',

				//Orders
				'order/success/<order_id:\w{8}>' => 'order/success',

				//Stripe
				'stripe/connect-back' => 'stripe/connect-back',

				//Login
				'login' => 'public/login',
				'forgot-password' => 'public/forgot-password',
				'reset-password' => 'public/reset-password',
				'authentication-required' => 'public/authentication-required',

				//Discover
				'discover-devisers' => 'discover/devisers',
				'discover-influencers' => 'discover/influencers',
				'explore-boxes' => 'discover/boxes',
				'stories' => 'discover/stories',
				'timeline' => 'person/timeline',

				//Chat / messages
				'messages' => 'chat/chat',
				'messages/<slug:[^/.]*?>/<person_id:[^/.]*?>' => 'chat/conversation',

				// Request become a Deviser
				'/become-a-deviser' => 'public/become-deviser',
				'/create-deviser-account' => 'public/create-deviser-account',

				// Request become an Influencer
				'/become-an-influencer' => 'public/become-influencer',
				'/create-influencer-account' => 'public/create-influencer-account',

				'/signup' => 'public/signup',

				//Links for a cart listing
				'cart/' => 'public/cart',
				'checkout/' => 'public/checkout',

				//Links for generics
				'contact' => 'public/contact',
				'about-us' => 'public/about-us',
				'cookies-policy' => 'public/cookies',
				'privacy-policy' => 'public/privacy',
				'terms-and-conditions' => 'public/terms',
				'returns-and-warranties' => 'public/returns',

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

				/*
				// Public routing
				'/index-old' => 'public/index-old',

				//links for terms
				'terms/' => 'public/terms-old',

				//Links for faq
				'faq/' => 'public/faq',

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

				*/
				/******************************************************************************************************/


				// API routing
				/* 'api/<action:[^/.]*?>/'  => 'api/<action>', */
				[
					'route' => 'api/<action>',
					'pattern' => 'api/<action:[^/.]*?>/',
					'suffix' => '/'
				],


				// API routing (public)
				'POST api3/pub/v1/invitation/request-become-deviser' => 'api3/pub/v1/invitation/request-become-deviser',
				'POST api3/pub/v1/invitation/request-become-influencer' => 'api3/pub/v1/invitation/request-become-influencer',

				'POST api3/pub/v1/newsletter' => 'api3/pub/v1/newsletter/create',

				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/product'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/faq'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/term'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/category'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/pub/v1/location'],
				'GET api3/pub/v1/countries' => 'api3/pub/v1/country/index',
				'GET api3/pub/v1/countries/worldwide' => 'api3/pub/v1/country/worldwide',
				'GET api3/pub/v1/countries/shipping' => 'api3/pub/v1/country/shipping',
				'GET api3/pub/v1/countries/eu-countries' => 'api3/pub/v1/country/eu-countries',
				'GET api3/pub/v1/countries/<countryCode:[^/.]*?>' => 'api3/pub/v1/country/view',
				'GET api3/pub/v1/invitations/<uuid:[^/.]*?>' => 'api3/pub/v1/invitation/view', // override "view" action to accept alphanumeric ids

				'POST api3/pub/v1/auth/login' => 'api3/pub/v1/auth/login',
				'POST api3/pub/v1/auth/forgot-password' => 'api3/pub/v1/auth/forgot-password',
				'POST api3/pub/v1/auth/reset-password' => 'api3/pub/v1/auth/reset-password',

				// Cart - public
				'POST api3/pub/v1/cart' => 'api3/pub/v1/cart/create-cart',
				'GET api3/pub/v1/cart/<cartId:[^/.]*?>' => 'api3/pub/v1/cart/view',
				'PUT api3/pub/v1/cart/<cartId:[^/.]*?>' => 'api3/pub/v1/cart/update',
				'POST api3/pub/v1/cart/<cartId:[^/.]*?>/product' => 'api3/pub/v1/cart/add-product',
				'DELETE api3/pub/v1/cart/<cartId:[^/.]*?>/product/<priceStockId:[^/.]*?>' => 'api3/pub/v1/cart/delete-product',
				'POST api3/pub/v1/cart/<cartId:[^/.]*?>/receiveToken' => 'api3/pub/v1/cart/receive-token',

				// Person - public
				'GET api3/pub/v1/person' => 'api3/pub/v1/person/index',
				'GET api3/pub/v1/person/<personId:[^/.]*?>' => 'api3/pub/v1/person/view',
				'POST api3/pub/v1/person' => 'api3/pub/v1/person/create',

				// Person - private
				'GET api3/priv/v1/person/<personId:[^/.]*?>' => 'api3/priv/v1/person/view',
				'PATCH api3/priv/v1/person/<personId:[^/.]*?>' => 'api3/priv/v1/person/update',
				'PUT api3/priv/v1/person/<personId:[^/.]*?>' => 'api3/priv/v1/person/update',
				'PUT api3/priv/v1/person/<personId:[^/.]*?>/update-password' => 'api3/priv/v1/person/update-password',

				// Person - orders and packs
				'GET api3/priv/v1/person/<personId:[^/.]*?>/orders' => 'api3/priv/v1/person/orders',
				'GET api3/priv/v1/person/<personId:[^/.]*?>/orders/<orderId:[^/.]*>' => 'api3/priv/v1/person/order',
				'GET api3/priv/v1/person/<personId:[^/.]*?>/packs' => 'api3/priv/v1/person/packs',
				'PUT api3/priv/v1/person/<personId:[^/.]*?>/packs/<packId:[^/.]*>/aware' => 'api3/priv/v1/person/pack-aware',
				'PUT api3/priv/v1/person/<personId:[^/.]*?>/packs/<packId:[^/.]*>/shipped' => 'api3/priv/v1/person/pack-shipped',

				// Person - followers
				'POST api3/priv/v1/follow/<personFollowedId:[^/.]*>' => 'api3/priv/v1/person/follow',
				'DELETE api3/priv/v1/follow/<personFollowedId:[^/.]*>' => 'api3/priv/v1/person/unfollow',


				// Product - public
				'GET api3/pub/v1/products/<id:[^/.]*?>' => 'api3/pub/v1/product/view',
				// Product - private
				'GET api3/priv/v1/products/<id:[^/.]*?>' => 'api3/priv/v1/product/view',
				'GET api3/priv/v1/products' => 'api3/priv/v1/product/index',
				'POST api3/priv/v1/products' => 'api3/priv/v1/product/create',
				'PATCH api3/priv/v1/products/<id:[^/.]*?>' => 'api3/priv/v1/product/update',
				'PUT api3/priv/v1/products/<id:[^/.]*?>' => 'api3/priv/v1/product/update',
				'DELETE api3/priv/v1/products/<id:[^/.]*?>' => 'api3/priv/v1/product/delete',

				// Loved - public
				'GET api3/pub/v1/loved/<lovedId:[^/.]*?>' => 'api3/pub/v1/loved/view',
				'GET api3/pub/v1/loved' => 'api3/pub/v1/loved/index',
				// Loved - private
				'GET api3/priv/v1/loved/<lovedId:[^/.]*?>' => 'api3/priv/v1/loved/view',
				'GET api3/priv/v1/loved' => 'api3/priv/v1/loved/index',
				'POST api3/priv/v1/loved' => 'api3/priv/v1/loved/create',
				'DELETE api3/priv/v1/loved/<productId:[^/.]*?>' => 'api3/priv/v1/loved/delete-product',
				'DELETE api3/priv/v1/loved/box/<boxId:[^/.]*?>' => 'api3/priv/v1/loved/delete-box',
				'DELETE api3/priv/v1/loved/post/<postId:[^/.]*?>' => 'api3/priv/v1/loved/delete-post',

				// Box - public
				'GET api3/pub/v1/box/<boxId:[^/.]*?>' => 'api3/pub/v1/box/view',
				'GET api3/pub/v1/box' => 'api3/pub/v1/box/index',
				// Box - private
				'GET api3/priv/v1/box' => 'api3/priv/v1/box/index',
				'GET api3/priv/v1/box/<boxId:[^/.]*?>' => 'api3/priv/v1/box/view',
				'POST api3/priv/v1/box' => 'api3/priv/v1/box/create',
				'PATCH api3/priv/v1/box/<boxId:[^/.]*?>' => 'api3/priv/v1/box/update',
				'DELETE api3/priv/v1/box/<boxId:[^/.]*?>' => 'api3/priv/v1/box/delete',
				'POST api3/priv/v1/box/<boxId:[^/.]*?>/product' => 'api3/priv/v1/box/add-product',
				'DELETE api3/priv/v1/box/<boxId:[^/.]*?>/product/<productId:[^/.]*?>' => 'api3/priv/v1/box/delete-product',

				// Story - public
				'GET api3/pub/v1/story/<storyId:[^/.]*?>' => 'api3/pub/v1/story/view',
				'GET api3/pub/v1/story' => 'api3/pub/v1/story/index',
				// Story - private
				'GET api3/priv/v1/story' => 'api3/priv/v1/story/index',
				'GET api3/priv/v1/story/<storyId:[^/.]*?>' => 'api3/priv/v1/story/view',
				'POST api3/priv/v1/story' => 'api3/priv/v1/story/create',
				'PATCH api3/priv/v1/story/<storyId:[^/.]*?>' => 'api3/priv/v1/story/update',
				'DELETE api3/priv/v1/story/<storyId:[^/.]*?>' => 'api3/priv/v1/story/delete',

				// Story - public
				'GET api3/pub/v1/post/<postId:[^/.]*?>' => 'api3/pub/v1/post/view',
				'GET api3/pub/v1/post' => 'api3/pub/v1/post/index',
				// Post - private
				'GET api3/priv/v1/post' => 'api3/priv/v1/post/index',
				'GET api3/priv/v1/post/<postId:[^/.]*?>' => 'api3/priv/v1/post/view',
				'POST api3/priv/v1/post' => 'api3/priv/v1/post/create',
				'PATCH api3/priv/v1/post/<postId:[^/.]*?>' => 'api3/priv/v1/post/update',
				'DELETE api3/priv/v1/post/<postId:[^/.]*?>' => 'api3/priv/v1/post/delete',

				
				// Banner - private
				'GET api3/priv/v1/banner' => 'api3/priv/v1/banner/index',
				'GET api3/priv/v1/banner/<bannerId:[^/.]*?>' => 'api3/priv/v1/banner/view',
				'POST api3/priv/v1/banner' => 'api3/priv/v1/banner/create',
				'PATCH api3/priv/v1/banner/<bannerId:[^/.]*?>' => 'api3/priv/v1/banner/update',
				'DELETE api3/priv/v1/banner/<bannerId:[^/.]*?>' => 'api3/priv/v1/banner/delete',

				// Chat - private
				'GET api3/priv/v1/chats' => 'api3/priv/v1/chat/index',
				'GET api3/priv/v1/chat/<chatId:[^/.]*?>' => 'api3/priv/v1/chat/view',
				'POST api3/priv/v1/chat/send-message/<personId:[^/.]*?>' => 'api3/priv/v1/chat/send-message',
				'PATCH api3/priv/v1/chat/mark-as-read/<chatId:[^/.]*?>' => 'api3/priv/v1/chat/mark-as-read',
				'PATCH api3/priv/v1/chat/mark-as-unread/<chatId:[^/.]*?>' => 'api3/priv/v1/chat/mark-as-unread',

				// Sizechart - public
				'GET api3/pub/v1/sizechart' => 'api3/pub/v1/sizechart/index',
				// Sizechart - private
				'GET api3/priv/v1/sizechart' => 'api3/priv/v1/sizechart/index',
				'POST api3/priv/v1/sizechart' => 'api3/priv/v1/sizechart/create',
				'PATCH api3/priv/v1/sizechart/<sizechartId:[^/.]*?>' => 'api3/priv/v1/sizechart/update',

				'GET api3/pub/v1/languages' => 'api3/pub/v1/language/index',

				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/priv/v1/upload'],

				// API routing (admin)
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/admin/v1/invitation'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/admin/v1/faq'],
				['class' => 'yii\rest\UrlRule', 'controller' => 'api3/admin/v1/term'],

			]
		],

		'user' => [
			'identityClass' => 'app\models\Person',
			'enableAutoLogin' => true,
			'enableSession' => true,
			'loginUrl' => ['/authentication-required'],
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
		'allowedIPs' => ['*'],
	];

	$config['bootstrap'][] = 'gii';
	$config['modules']['gii'] = 'yii\gii\Module';
}

return $config;
