<?php
namespace app\assets\desktop\settings;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/settings/settings.js',
	];
	public $depends = [
		'app\assets\libs\angularAsset',
		'app\assets\desktop\pub\PublicCommonAsset'
	];
}