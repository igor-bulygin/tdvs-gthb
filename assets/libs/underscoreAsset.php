<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class underscoreAsset extends AssetBundle {
	public $sourcePath = '@npm/underscore';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'underscore.js' : 'underscore-min.js'
	];
	public $depends = [
	];
}
