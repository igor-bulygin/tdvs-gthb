<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/pub/socialicious.css',
		'css/desktop/pub/global.css',
	];
	public $js = [
		'js/desktop/pub/global.js'
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset'
	];
}
