<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class lokijsAsset extends AssetBundle {
	public $sourcePath = '@npm/lokijs';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'src/lokijs.js' : 'build/lokijs.min.js'
	];
	public $depends = [
	];
}
