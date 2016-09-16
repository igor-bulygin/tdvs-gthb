<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class textAngularAsset extends AssetBundle {
	public $sourcePath = '@npm/textangular/dist/';
	public $css = [
		'textAngular.css'
	];
	public $js = [
		'textAngular-rangy.min.js',
		'textAngularSetup.js',
		YII_ENV_DEV ? 'textAngular-sanitize.js' : 'textAngular-sanitize.min.js',
		YII_ENV_DEV ? 'textAngular.js' : 'textAngular.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset',
	];
}
