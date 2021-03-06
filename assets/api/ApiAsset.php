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
		'js/api/services.js',
		'js/api/uploadDataService.js',
		'js/api/personDataService.js',
		'js/api/invitationDataService.js',
		'js/api/productDataService.js',
		'js/api/languageDataService.js',
		'js/api/sizechartDataService.js',
		'js/api/locationDataService.js',
		'js/api/tagDataService.js',
		'js/api/metricDataService.js',
		'js/api/cartDataService.js',
		'js/api/orderDataService.js',
		'js/api/lovedDataService.js',
		'js/api/boxDataService.js',
		'js/api/storyDataService.js',
		'js/api/newsletterDataService.js',
		'js/api/bannerDataService.js',
		'js/api/chatDataService.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset',
		'app\assets\libs\angularResourceAsset',
		'app\assets\libs\ngFileUploadAsset',
		'app\assets\libs\utilAsset',
	];
}