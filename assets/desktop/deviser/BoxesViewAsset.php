<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class BoxesViewAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
    	'js/desktop/person/store-grid.js'
    ];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\libs\macyAsset',
	];
}
