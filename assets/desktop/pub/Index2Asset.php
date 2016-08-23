<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class Index2Asset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/desktop/public-2/bootstrap.min.css',
        'css/desktop/public-2/application.css',
    ];
    public $js = [
        'js/desktop/public-2/bootstrap.min.js'
    ];
//	public $depends = [
//		'app\assets\desktop\pub\GlobalAsset',
//		'app\assets\libs\justifiedGalleryAsset'
//	];
}
