<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class CategoriesAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/categories.css'
	];
	public $js = [
		'js/desktop/admin/categories.js',
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\ngjstreeAsset',
		'app\assets\libs\jstreeActionsAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\libs\utilAsset',
		'app\assets\desktop\admin\GlobalAsset'
	];
}