<?php
namespace app\assets\desktop\product;

use yii\web\AssetBundle;

class ProductDetailAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
		'js/desktop/product/detail.js',
		'js/desktop/product/products-grid.js',
		//'js/desktop/product/select-selector/select-selector.js',
		//'js/desktop/product/color-selector/color-selector.js',
		//'js/desktop/product/size-selector/size-selector.js',
	];
	public $depends = [
		'app\assets\desktop\product\IndexAsset',
	];
}