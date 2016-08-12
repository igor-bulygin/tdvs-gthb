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
		'js/util/util.js',
		'js/util/treeService.js'
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\ngjstreeAsset',
		'app\assets\libs\jstreeActionsAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\desktop\admin\GlobalAsset'
	];
}