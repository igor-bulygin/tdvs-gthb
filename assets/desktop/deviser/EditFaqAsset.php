<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class EditFaqAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/deviser/edit-faq.js',
	];
	public $depends = [
		'app\assets\desktop\deviser\IndexAsset',
	];
}