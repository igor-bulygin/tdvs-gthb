<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularResourceAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-resource';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'angular-resource.js' : 'angular-resource.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}