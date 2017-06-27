<?php
namespace app\assets\desktop;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/global.css',
		'css/desktop/public-2/application.css',
		'css/desktop/public-2/bootstrap-select.min.css',
	];
	public $js = [
		'js/desktop/global.js',
		'js/desktop/todevise.module.js',
		'js/desktop/public-2/bootstrap-select.min.js',
	];
	public $depends = [
		'app\assets\AppAsset',
		'app\components\assets\PublicHeader2Asset',
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
		'app\assets\desktop\box\IndexAsset',
		'app\assets\libs\angularToastrAsset',
		'app\assets\desktop\deviser\IndexAsset',
		'app\assets\desktop\product\IndexAsset',
	];
}
