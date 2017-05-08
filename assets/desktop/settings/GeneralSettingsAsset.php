<?php
namespace app\assets\desktop\settings;

use yii\web\AssetBundle;

class GeneralSettingsAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/settings/general/general.js',
	];
	public $depends = [
		'app\assets\desktop\settings\IndexAsset',
	];
}