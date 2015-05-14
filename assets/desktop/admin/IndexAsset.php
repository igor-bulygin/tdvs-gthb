<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/index.css'
	];
	public $js = [
		'js/desktop/admin/index.js'
	];
	public $depends = [
		'app\assets\desktop\admin\GlobalAdminAsset'
	];
}
