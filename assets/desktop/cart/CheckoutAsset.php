<?php
namespace app\assets\desktop\cart;

use yii\web\AssetBundle;

class CheckoutAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/cart/checkout.js',
		'js/desktop/cart/summary/summary.js',
		'js/desktop/cart/shopping-cart/shopping-cart.js'
	];
	public $depends = [
		'app\assets\desktop\cart\CartAsset',
	];
}