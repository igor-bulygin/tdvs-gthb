<?php
namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/fonts.css',
		'css/global.css',
		'css/bootstrap-patches.css',
	];
	public $js = [
		'js/global.js'
	];
	public $depends = [
		'yii\web\YiiAsset',
		'app\assets\libs\bootstrapAsset',
		'app\assets\libs\angularAsset',
	];
}
