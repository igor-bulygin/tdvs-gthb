<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularFilterAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-filter/dist';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'angular-filter.js' : 'angular-filter.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
