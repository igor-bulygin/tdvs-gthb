<?php

namespace app\assets\desktop;

use yii\web\AssetBundle;

class jstreerAsset extends AssetBundle
{
	public $sourcePath = '@npm/jstree/dist';
	public $css = [
		YII_ENV_DEV ? 'themes/default-dark/style.min.css' : 'themes/default-dark/style.css'
	];
	public $js = [
		YII_ENV_DEV ? 'jstree.min.js' : 'jstree.js'
	];
	public $depends = [
		'yii\web\JqueryAsset',
	];
}
