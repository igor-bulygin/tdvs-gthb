<?php
namespace app\assets\desktop\product;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
		'js/desktop/product/detail.js',
		'js/desktop/product/detail-grid.js',
		'js/desktop/product/create-product.js',
		'js/desktop/product/basic-info/basic-info.js',
		'js/desktop/product/more-details/more-details.js',
		'js/desktop/product/variations/variations.js',
		'js/desktop/product/price-stock/price-stock.js',
		'js/desktop/product/xeditable-select.js',
		'js/desktop/product/edit-product.js',		
		'js/desktop/cart/cart-panel/cart-panel.js'
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
		'app\assets\desktop\product\IndexAsset',
	];
}