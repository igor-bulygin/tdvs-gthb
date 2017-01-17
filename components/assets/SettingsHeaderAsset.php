<?php
namespace app\components\assets;

use yii\web\AssetBundle;

class SettingsHeaderAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/components/settingsHeader.js'
	];
	public $depends = [
		'app\assets\desktop\settings\IndexAsset'
	];
}