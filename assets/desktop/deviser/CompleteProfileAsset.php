<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class CompleteProfileAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/person/complete-profile.js',
	];
	public $depends = [
		'app\assets\desktop\deviser\IndexAsset',
	];
}