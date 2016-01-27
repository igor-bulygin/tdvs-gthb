<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class ngjstreeAsset extends AssetBundle
{
	public $sourcePath = '@npm/ng-js-tree/dist';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'ngJsTree.js' : 'ngJsTree.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset',
		'app\assets\libs\jstreeAsset'
	];
}
