<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularMessagesAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-messages';
	public $css = [];
	public $js = [
		YII_ENV_DEV ? 'angular-messages.js' : 'angular-messages.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}