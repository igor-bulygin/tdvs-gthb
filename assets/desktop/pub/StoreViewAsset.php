<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class StoreViewAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
    	'js/desktop/deviser/store-grid.js',
    ];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\libs\macyAsset',
	];
}
