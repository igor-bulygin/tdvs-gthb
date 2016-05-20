<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class CategoryAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/pub/category.css'
	];
	public $js = [
		'js/desktop/pub/category.js'
	];
	public $depends = [
		'app\assets\desktop\pub\GlobalAsset',
		'app\assets\libs\justifiedGalleryAsset'
	];
}
