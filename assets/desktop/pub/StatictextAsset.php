<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class StatictextAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/pub/statictext.css'
	];
	
	public $depends = [
		'app\assets\desktop\pub\GlobalAsset',
		'app\assets\libs\socialShareKitAsset',
		'app\assets\libs\justifiedGalleryAsset'
	];
}
