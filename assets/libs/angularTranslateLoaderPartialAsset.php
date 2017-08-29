<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularTranslateLoaderPartialAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-translate-loader-partial';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'angular-translate-loader-partial.js' : 'angular-translate-loader-partial.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset',
		'app\assets\libs\angularTranslateAsset'
	];
}