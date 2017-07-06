<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/global.css',
		'css/desktop/admin/global.css',
	];
	public $js = [
		'js/global.js',
		'js/desktop/global.js',
		'js/desktop/admin/global.js',
	];
	public $depends = [
		'app\assets\libs\angularToastrAsset',
		'app\assets\libs\underscoreAsset',
	];
}
