<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularTranslateAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-translate';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'dist/angular-translate.js' : 'dist/angular-translate.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}