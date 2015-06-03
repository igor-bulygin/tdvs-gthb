<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/global.css'
	];
	public $js = [
		'js/desktop/admin/global.js'
	];
	public $depends = [
		'app\assets\libs\angularToastrAsset',
		'app\assets\libs\underscoreAsset',
		'app\assets\desktop\GlobalAsset'
	];
}
