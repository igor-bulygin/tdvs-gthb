<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class SizeChartsAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/sizecharts.css'
	];
	public $js = [
		'js/desktop/admin/sizecharts.js'
	];
	public $depends = [
		'app\assets\desktop\admin\GlobalAsset',
		'app\assets\libs\angularMultiSelectAsset',
		'app\assets\libs\angularBootstrapAsset',
	];
}
