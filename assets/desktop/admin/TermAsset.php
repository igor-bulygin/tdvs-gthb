<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class TermAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/term.css'
	];
	public $js = [
		'js/desktop/admin/term.js'
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\ngjstreeAsset',
		'app\assets\libs\jstreeActionsAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\desktop\admin\GlobalAsset'
	];
}
