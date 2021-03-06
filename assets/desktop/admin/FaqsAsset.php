<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class FaqsAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/faqs.css'
	];
	public $js = [
		'js/desktop/admin/faqs.js',		
		'js/api/faqDataService.js'
	];
	public $depends = [
		'app\assets\desktop\admin\GlobalAsset',
		'app\assets\libs\ngjstreeAsset',
		'app\assets\libs\jstreeActionsAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\libs\utilAsset',
	];
}