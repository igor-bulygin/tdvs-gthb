<?php

Yii::setAlias('@tests', dirname(__DIR__) . '/tests');

return [
	'id' => 'basic-console',
	'basePath' => dirname(__DIR__),
	'bootstrap' => ['log', 'gii'],
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
		]
	],
	'params' => require(__DIR__ . '/params.php'),
];
