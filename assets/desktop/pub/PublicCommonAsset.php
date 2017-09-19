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
		'js/desktop/public-2/become-influencer.js',
		'js/desktop/public-2/create-account.js',
		'js/desktop/public-2/login.js',
		'js/desktop/public-2/authentication-required.js',
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
	];
}
