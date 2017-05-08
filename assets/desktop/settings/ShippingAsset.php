<?php
namespace app\assets\desktop\settings;

use yii\web\AssetBundle;

class ShippingAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
		'js/desktop/settings/shipping/shipping.js',
		'js/desktop/settings/shipping/zones/zones.js',
		'js/desktop/settings/shipping/weights/weights.js',
		'js/desktop/settings/shipping/prices/prices.js',
		'js/desktop/settings/shipping/observations/observations.js',
	];
	public $depends = [
		'app\assets\desktop\settings\IndexAsset',
	];
}