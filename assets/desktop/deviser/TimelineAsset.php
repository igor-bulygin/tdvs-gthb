<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class TimelineAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
		'js/desktop/person/social-feed/timeline/timeline.js',
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
		'app\assets\desktop\deviser\IndexAsset',
		'app\assets\libs\momentAsset',
	];
}