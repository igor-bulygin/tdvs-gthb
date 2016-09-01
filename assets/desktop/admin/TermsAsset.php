<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class TermsAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/terms.css'
	];
	public $js = [
		'js/desktop/admin/terms.js',
		'js/api/termDataService.js'
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