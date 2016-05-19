<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class DeviserAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/pub/deviser.css'
	];
	public $js = [
		'js/desktop/pub/deviser.js'
	];
	public $depends = [
		'app\assets\desktop\pub\GlobalAsset',
		'app\assets\libs\justifiedGalleryAsset'
	];
}
