<?php
namespace app\assets\desktop\settings;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/settings/general/general.js',
    'js/desktop/settings/affiliates/affiliates.js',
		'js/desktop/settings/shipping/shipping.js',
		'js/desktop/settings/shipping/types/types.js',
		'js/desktop/settings/shipping/weights-prices/weights-prices.js',
		'js/desktop/settings/shipping/observations/observations.js',
		'js/desktop/settings/order/orders.js',
		'js/desktop/settings/order/sold-orders/sold-orders.js',
		'js/desktop/settings/order/bought-orders/bought-orders.js'
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
		'app\assets\desktop\settings\IndexAsset'
	];
}