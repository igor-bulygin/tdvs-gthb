<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class jsonpathAsset extends AssetBundle {
	public $sourcePath = '@npm/jsonpath';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'jsonpath.js' : 'jsonpath.min.js'
	];
	public $depends = [
		'app\assets\libs\underscoreAsset'
	];
}
