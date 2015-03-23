<?php

namespace app\assets\tablet;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle
{
	public $basePath = '@webroot';
    public $baseUrl = '@web';
	public $css = [
		'css/tablet/global.css'
	];
	public $js = [
	];
	public $depends = [
	];
}
