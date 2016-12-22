<?php
namespace app\components\assets;

use yii\web\AssetBundle;

class PublicHeader2Asset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/components/header-2.js',
		'js/desktop/public-2/public-header.js',
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
	];
}
