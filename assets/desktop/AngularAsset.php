<?php

namespace app\assets\desktop;

use yii\web\AssetBundle;

class AngularAsset extends AssetBundle
{
	public $sourcePath = '@npm/angular';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'angular.min.js' : 'angular.js'
	];
	public $depends = [
		'yii\web\JqueryAsset',
	];
}
