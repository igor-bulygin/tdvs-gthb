<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class decimaljsAsset extends AssetBundle {
	public $sourcePath = '@npm/decimal.js';
	public $css = [];
	public $js = [
		YII_ENV_DEV ? 'decimal.js' : 'decimal.min.js'
	];
	public $depends = [];
}
