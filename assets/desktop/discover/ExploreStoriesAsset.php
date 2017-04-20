<?php
namespace app\assets\desktop\discover;

use yii\web\AssetBundle;

class ExploreStoriesAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/discover/stories/exploreStoriesCtrl.js',
		'js/desktop/discover/stories/results/results.js',
	];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
	];
}