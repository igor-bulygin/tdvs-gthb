<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class jstreeActionsAsset extends AssetBundle
{
	public $sourcePath = '@npm/jstree-actions';
	public $css = [
	];
	public $js = [
		//TODO: change to .min.js
		YII_ENV_DEV ? 'jstree-actions.js' : 'jstree-actions.js'
	];
	public $depends = [
		'app\assets\libs\jstreeAsset'
	];
}
