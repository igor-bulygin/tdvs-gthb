<?php

require(__DIR__ . '/../vendor/autoload.php');

// load .env file values
$dotenv = new Dotenv\Dotenv(__DIR__.'/..');
$dotenv->load();

if(getenv("DEV") == "1"){
	define('YII_DEBUG', true);
	define('YII_ENV', 'dev');
}
define('ENVIRONMENT', getenv('ENVIRONMENT'));

require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

/* Require all helper files */
foreach(glob(__DIR__ . "/../helpers/*.php") as $file) {
	require($file);
}

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
