<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class CartAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/pub/cart.css'
	];
	public $js = [
		'js/desktop/pub/cart.js'
	];
	public $depends = [
		'app\assets\desktop\pub\GlobalAsset',
		'app\assets\libs\socialShareKitAsset',
		'app\assets\libs\justifiedGalleryAsset'
	];
}
