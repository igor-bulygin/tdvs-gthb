<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class PublicCommonAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/public-2/login-2.css',
	];
	public $js = [
		'js/desktop/public-2/become-deviser.js',
		'js/desktop/public-2/create-account.js',
		'js/desktop/public-2/login.js',
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
	];
}
