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
	];
	public $depends = [
		'app\assets\desktop\settings\IndexAsset',
	];
}