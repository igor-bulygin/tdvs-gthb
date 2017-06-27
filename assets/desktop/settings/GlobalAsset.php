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
		'js/desktop/settings/billing/billing.js',
		'js/desktop/settings/billing/australia/australia.js',
		'js/desktop/settings/billing/canada/canada.js',
		'js/desktop/settings/billing/usa/usa.js',
		'js/desktop/settings/billing/newZealand/newZealand.js',
		'js/desktop/settings/billing/other/otherBankInformation.js',
		'js/desktop/settings/billing/showBillingErrors.js'
	];
	public $depends = [
		'app\assets\desktop\settings\IndexAsset',
		'app\assets\desktop\GlobalAsset',
	];
}