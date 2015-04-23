<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class AngularAsset extends AssetBundle {
	public $sourcePath = '@npm/angular';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'angular.js' : 'angular.min.js'
	];
	public $depends = [
		'yii\web\JqueryAsset',
	];
}
