<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularCookiesAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-cookies';
	public $css = [];
	public $js = [
		YII_ENV_DEV ? 'angular-cookies.js' : 'angular-cookies.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}