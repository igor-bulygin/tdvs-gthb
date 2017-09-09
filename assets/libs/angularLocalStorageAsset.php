<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularLocalStorageAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-local-storage';
	public $css = [];
	public $js = [
		YII_ENV_DEV ? 'src/angular-local-storage.js' : 'dist/angular-local-storage.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}