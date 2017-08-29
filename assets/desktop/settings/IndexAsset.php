<?php
namespace app\assets\desktop\settings;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/settings/settings.js',
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
		'app\components\assets\PublicHeader2Asset',
		'app\assets\libs\textAngularAsset',
		'app\assets\libs\nyaBootstrapSelectAsset',
	];
}