<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularTranslateLoadingFilesAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-translate-loader-static-files';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'angular-translate-loader-static-files.js' : 'angular-translate-loader-static-files.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset',
		'app\assets\libs\angularTranslateAsset'
	];
}