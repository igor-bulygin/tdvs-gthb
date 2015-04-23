<?php

namespace app\assets\desktop;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle
{
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/global.css'
	];
	public $js = [
	];
	public $depends = [
	];
}
