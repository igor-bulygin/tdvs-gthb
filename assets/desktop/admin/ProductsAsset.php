<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class ProductsAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/products.css'
	];
	public $js = [
		'js/desktop/admin/products.js'
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\desktop\admin\GlobalAsset'
	];
}
