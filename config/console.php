<?php

Yii::setAlias('@webroot', __DIR__ . '/../../web');
Yii::setAlias('@web', '/');
Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

return [
	'id' => 'basic-console',
	'basePath' => dirname(__DIR__),
	'bootstrap' => [
		'log',
		'app\components\Aliases',
		'gii',
	],
	'controllerNamespace' => 'app\commands',
	'modules' => [
		'gii' => 'yii\gii\Module',
	],
	'controllerMap' => [
		'mongodb-migrate' => 'yii\mongodb\console\controllers\MigrateController'
	],
	'components' => [
		'cache' => [
			'class' => 'yii\caching\FileCache',
		],

		//i18n
		'i18n' => [
			'translations' => [
				'app*' => [
					'class' => 'yii\i18n\PhpMessageSource',
					'fileMap' => [
						'app' => 'app.php',
						'app/admin' => 'app/admin.php',
						'app/public' => 'app/public.php'
					]
				]
			]
		],

		'log' => [
			'targets' => [
				[
					'class' => 'yii\log\FileTarget',
					'levels' => ['error', 'warning'],
				],
			],
		],
		//Database
		'mongodb' => require(__DIR__ . '/db.php'),

		'Scrypt' => [
			'class' => 'alexandernst\Scrypt\Scrypt'
		],


		//URLs
		'urlManager' => [
			'hostInfo' => YII_ENV_PROD ? 'https://todevise.com' : 'http://localhost:8080',
			'baseUrl' => '',
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

				//Links for contact
				'contact' => 'public/contact',
				'about-us' => 'public/about-us',
				'cookies-policy' => 'public/cookies',
				'privacy-policy' => 'public/privacy',
				'terms-and-conditions' => 'public/terms',

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
				'DELETE api3/priv/v1/loved/<productId:[^/.]*?>' => 'api3/priv/v1/loved/delete',

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
				'GET api3/pub/v1/story/<boxId:[^/.]*?>' => 'api3/pub/v1/story/view',
				'GET api3/pub/v1/story' => 'api3/pub/v1/story/index',
				// Story - private
				'GET api3/priv/v1/story' => 'api3/priv/v1/story/index',
				'GET api3/priv/v1/story/<storyId:[^/.]*?>' => 'api3/priv/v1/story/view',
				'POST api3/priv/v1/story' => 'api3/priv/v1/story/create',
				'PATCH api3/priv/v1/story/<storyId:[^/.]*?>' => 'api3/priv/v1/story/update',
				'DELETE api3/priv/v1/story/<storyId:[^/.]*?>' => 'api3/priv/v1/story/delete',

				// Banner - private
				'GET api3/priv/v1/banner' => 'api3/priv/v1/banner/index',
				'GET api3/priv/v1/banner/<bannerId:[^/.]*?>' => 'api3/priv/v1/banner/view',
				'POST api3/priv/v1/banner' => 'api3/priv/v1/banner/create',
				'PATCH api3/priv/v1/banner/<bannerId:[^/.]*?>' => 'api3/priv/v1/banner/update',
				'DELETE api3/priv/v1/banner/<bannerId:[^/.]*?>' => 'api3/priv/v1/banner/delete',

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
	],
	'params' => require(__DIR__ . '/params.php'),
];
