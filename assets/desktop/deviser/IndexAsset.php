<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/deviser/index.css'
	];
	public $js = [
		'js/desktop/deviser/index.js'
	];
	public $depends = [
		'app\assets\desktop\deviser\GlobalAsset'
	];
}
