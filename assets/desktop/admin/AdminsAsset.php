<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class AdminsAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/admins.css'
	];
	public $js = [
		'js/desktop/admin/admins.js'
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\desktop\admin\GlobalAsset'
	];
}
