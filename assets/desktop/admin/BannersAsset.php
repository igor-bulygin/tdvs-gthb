<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class BannersAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
//		'css/desktop/admin/banners.css'
	];
	public $js = [
//		'js/desktop/admin/banners.js'
	];
	public $depends = [
		'app\assets\desktop\admin\GlobalAsset',
	];
}
