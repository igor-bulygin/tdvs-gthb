<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularAnimateAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-animate';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'angular-animate.js' : 'angular-animate.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
