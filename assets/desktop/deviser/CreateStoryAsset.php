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
		'js/desktop/person/stories/text-component/text-component.js',
		'js/desktop/person/stories/photo-component/photo-component.js',
		'js/desktop/person/stories/video-component/video-component.js',
		'js/desktop/person/stories/work-component/work-component.js',
	];
	public $depends = [
		'app\assets\desktop\deviser\IndexAsset',
		'app\assets\libs\nyaBootstrapSelectAsset',
		'app\assets\libs\ngFileUploadAsset',
		'app\assets\libs\angularXEditableAsset',
	];
}