<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularMomentAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-moment';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'angular-moment.js' : 'min/angular-moment.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}