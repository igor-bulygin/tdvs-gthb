<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class ProductAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/pub/product.css'
	];
	public $js = [
		'js/desktop/pub/product.js'
	];
	public $depends = [
		'app\assets\desktop\pub\GlobalAsset'
	];
}
