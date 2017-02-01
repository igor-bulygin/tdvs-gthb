<?php
namespace app\assets\desktop\cart;

use yii\web\AssetBundle;

class CartAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/cart/cart.js',
		'js/desktop/cart/cartService.js',
		'https://checkout.stripe.com/checkout.js',
		'https://js.stripe.com/v2/',
	];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
	];
}