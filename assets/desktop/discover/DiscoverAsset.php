<?php
namespace app\assets\desktop\discover;

use yii\web\AssetBundle;

class DiscoverAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/discover/person/discoverCtrl.js',
		'js/desktop/discover/person/results/results.js',
		'js/desktop/discover/person/card/card.js',
		'js/desktop/discover/person/filters/filters.js',
	];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
	];
}