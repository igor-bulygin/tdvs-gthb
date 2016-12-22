<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class ProductsAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
		'js/desktop/pub/products.js'
	];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\libs\macyAsset',
	];
}
