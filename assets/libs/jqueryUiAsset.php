<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class jqueryUiAsset extends AssetBundle {
	public $sourcePath = '@npm/jquery-ui-dist';
	public $css = [
		YII_ENV_DEV ? 'jquery-ui.css' : 'jquery-ui.min.css'
	];
	public $js = [
		YII_ENV_DEV ? 'jquery-ui.js' : 'jquery-ui.min.js'
	];
	public $depends = [
		'yii\web\JqueryAsset'
	];
}
