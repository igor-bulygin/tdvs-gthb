<?php
namespace app\assets\desktop\settings;

use yii\web\AssetBundle;

class BillingAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
		'js/desktop/settings/billing/billing.js',
		'js/desktop/settings/billing/canada/canada.js',
		'js/desktop/settings/billing/usa/usa.js',
		'js/desktop/settings/billing/newZealand/newZealand.js',
		'js/desktop/settings/billing/other/otherBankInformation.js'
	];
	public $depends = [
		'app\assets\desktop\settings\IndexAsset',
	];
}