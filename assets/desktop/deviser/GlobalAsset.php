<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/deviser/global.css'
	];
	public $js = [
		'js/desktop/deviser/global.js'
	];
	public $depends = [
		'app\assets\libs\angularToastrAsset',
		'app\assets\libs\underscoreAsset',
		'app\assets\desktop\GlobalAsset'
	];
}
