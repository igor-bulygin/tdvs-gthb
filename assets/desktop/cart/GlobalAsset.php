<?php
namespace app\assets\desktop\cart;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'https://checkout.stripe.com/checkout.js',
		'https://js.stripe.com/v2/',
		'js/desktop/cart/checkout.js',
		'js/desktop/cart/summary/summary.js',
		'js/desktop/cart/shopping-cart/shopping-cart.js',
		'js/desktop/cart/personal-info/personal-info.js',
		'js/desktop/cart/payment-methods/payment-methods.js',
		'js/desktop/order/success.js',
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
		'app\assets\desktop\cart\IndexAsset',
	];
}