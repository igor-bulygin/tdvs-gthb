<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class Login2Asset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/desktop/public-2/login-2.css',
    ];
    public $js = [
    	'js/desktop/public-2/login.js',
    ];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\api\ApiAsset',
		'app\assets\libs\angularAsset',
		'app\assets\libs\utilAsset'
	];
}
