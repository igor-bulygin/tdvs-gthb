<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class StoryDetailAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/person/stories/detail-story.js',
	];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
	];
}
