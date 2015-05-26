<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularMultiSelectAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-multi-select';
	public $css = [
		YII_ENV_DEV ? 'angular-multi-select.css' : 'angular-multi-select.min.css'
	];
	public $js = [
		YII_ENV_DEV ? 'angular-multi-select.js' : 'angular-multi-select.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
