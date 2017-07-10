<?php
namespace app\assets\desktop\settings;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/settings/general/general.js',
		'js/desktop/settings/shipping/shipping.js',
		'js/desktop/settings/shipping/types/types.js',
		'js/desktop/settings/shipping/weights-prices/weights-prices.js',
		'js/desktop/settings/shipping/observations/observations.js',
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
		'app\assets\desktop\settings\IndexAsset',
	];
}