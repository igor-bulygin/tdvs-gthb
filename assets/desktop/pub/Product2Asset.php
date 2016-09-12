<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class Product2Asset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
	public $css = [
	];
    public $js = [
		'js/desktop/product/detail.js',
		'js/desktop/product/select-selector/select-selector.js',
		'js/desktop/product/color-selector/color-selector.js',
		'js/desktop/product/size-selector/size-selector.js'
    ];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\libs\angularAsset',
		'app\assets\libs\angularToastrAsset',
		'app\assets\api\ApiAsset',
	];
}