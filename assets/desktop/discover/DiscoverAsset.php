<?php
namespace app\assets\desktop\discover;

use yii\web\AssetBundle;

class DiscoverAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/discover/discoverCtrl.js',
		'js/desktop/discover/results/results.js',
		'js/desktop/discover/card/card.js',
	];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
	];
}