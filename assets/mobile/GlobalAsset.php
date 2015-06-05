<?php

namespace app\assets\mobile;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle{
	public $basePath = '@webroot';
    public $baseUrl = '@web';
	public $css = [
		'css/mobile/global.css'
	];
	public $js = [
		'js/mobile/global.js'
	];
	public $depends = [
	];
}
