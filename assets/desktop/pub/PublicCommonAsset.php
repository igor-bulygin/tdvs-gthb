<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class PublicCommonAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/desktop/public-2/application.css',
        'css/desktop/public-2/bootstrap-select.min.css',
    ];
    public $js = [
        'js/desktop/public-2/bootstrap-select.min.js',
    ];
	public $depends = [
		'app\assets\libs\bootstrapAsset',
		'app\assets\libs\angularAsset',
		'app\components\assets\PublicHeader2Asset',
		'app\assets\desktop\GlobalAsset',
	];
}
