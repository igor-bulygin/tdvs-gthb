<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class CreateStoryAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/person/stories/create-story.js',
		'js/desktop/person/stories/main-title/main-title.js',
		'js/desktop/person/stories/main-media/main-media.js',
	];
	public $depends = [
		'app\assets\desktop\deviser\IndexAsset',
	];
}