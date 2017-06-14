<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class DeviserNotPublicAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/person/deviser-not-public.js',
	];
	public $depends = [
		'app\assets\desktop\deviser\IndexAsset',
	];
}