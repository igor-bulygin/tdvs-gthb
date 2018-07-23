<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class blazyAsset extends AssetBundle {
	public $sourcePath = '@npm/blazy';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'blazy.js' : 'blazy.min.js'
	];
	public $depends = [
	];
}
