<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class PersonNotPublicAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/person/person-not-public.js',
	];
	public $depends = [
		'app\assets\desktop\deviser\IndexAsset',
	];
}