<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class PublicCommonAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [
        'css/desktop/public-2/application.css',
    ];
    public $js = [];
	public $depends = [
		'app\assets\libs\bootstrapAsset'
	];
}
