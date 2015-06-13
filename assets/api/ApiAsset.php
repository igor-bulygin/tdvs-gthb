<?php
namespace app\assets\api;

use yii\web\AssetBundle;

class ApiAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
		'js/api/api.js',
		'js/api/factories.js',
		'js/api/services.js'
	];
	public $depends = [
		'app\assets\AppAsset',
		'app\assets\libs\angularAsset',
		'app\assets\libs\jsonpathAsset'
	];
}
