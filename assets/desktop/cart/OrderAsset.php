<?php
namespace app\assets\desktop\cart;

use yii\web\AssetBundle;

class OrderAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/cart/success.js',
		'js/desktop/cart/summary/summary.js',
	];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\desktop\cart\CartAsset',
	];
}