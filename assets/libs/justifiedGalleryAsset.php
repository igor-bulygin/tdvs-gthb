<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class justifiedGalleryAsset extends AssetBundle {
	public $sourcePath = '@npm/justifiedGallery';
	public $css = [
		YII_ENV_DEV ? 'dist/css/justifiedGallery.css' : 'dist/css/justifiedGallery.min.css'
	];
	public $js = [
		YII_ENV_DEV ? 'dist/js/jquery.justifiedGallery.js' : 'dist/js/jquery.justifiedGallery.min.js'
	];
	public $depends = [];
}
