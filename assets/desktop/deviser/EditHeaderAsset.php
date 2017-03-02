<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class EditHeaderAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/person/edit-header.js'
	];
	public $depends = [
		'app\assets\desktop\deviser\IndexAsset',
	];
}