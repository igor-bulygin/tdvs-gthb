<?php

define('TODEVISE_DEV', strpos($_SERVER['HTTP_HOST'], 'dev.todevise.com') !== false);
define('TODEVISE_BETA', strpos($_SERVER['HTTP_HOST'], 'beta.todevise.com') !== false);
define('TODEVISE_PROD', strpos($_SERVER['HTTP_HOST'], 'www.todevise.com') !== false);

if(!TODEVISE_BETA && getenv("DEV") == "1"){
	define('YII_DEBUG', true);
	define('YII_ENV', 'dev');
}

define('STRIPE_LIVE_MODE', TODEVISE_PROD || TODEVISE_BETA ? true : false);

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');

/* Require all helper files */
foreach(glob(__DIR__ . "/../helpers/*.php") as $file) {
	require($file);
}

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
