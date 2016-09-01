<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class Product2Asset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
	public $css = [
		'css/desktop/public-2/bootstrap-select.min.css',
	];
    public $js = [
	    'js/desktop/public-2/bootstrap-select.min.js',
		'js/desktop/product/detail.js'
    ];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\desktop\pub\GlobalAsset'
	];
}