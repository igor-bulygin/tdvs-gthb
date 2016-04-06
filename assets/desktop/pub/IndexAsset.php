<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/pub/index.css'
	];
	public $js = [
		'js/desktop/pub/index.js'
	];
	public $depends = [
		'app\assets\desktop\pub\GlobalAsset',
		'app\assets\libs\justifiedGalleryAsset'
	];
}
