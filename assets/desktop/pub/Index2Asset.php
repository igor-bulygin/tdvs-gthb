<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class Index2Asset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
		'js/desktop/public-2/index-2.js'
	];
	public $depends = [
		'app\assets\libs\macyAsset',
		'app\assets\desktop\pub\PublicCommonAsset',
	];
}
