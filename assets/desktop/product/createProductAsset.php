<?php
namespace app\assets\desktop\product;

use yii\web\AssetBundle;

class createProductAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/product/create-product.js',
		'js/desktop/product/basic-info/basic-info.js'
	];
	public $depends = [
		'app\assets\desktop\product\IndexAsset',
	];
}