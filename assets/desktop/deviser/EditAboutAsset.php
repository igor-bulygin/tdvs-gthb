<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class EditAboutAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/deviser/edit-about.js',
	];
	public $depends = [
		'app\assets\desktop\deviser\IndexAsset',
	];
}