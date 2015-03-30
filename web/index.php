<?php

if(getenv("DEV") === "1"){
	define('YII_DEBUG', true);
	define('YII_ENV', 'dev');
}

require(__DIR__ . '/../vendor/autoload.php');
require(__DIR__ . '/../vendor/yiisoft/yii2/Yii.php');
require(__DIR__ . '/../helpers/Utils.php');

$config = require(__DIR__ . '/../config/web.php');

(new yii\web\Application($config))->run();
