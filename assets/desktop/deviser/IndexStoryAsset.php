<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class IndexStoryAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/person/stories/main-title/main-title.js',
		'js/desktop/person/stories/main-media/main-media.js',
		'js/desktop/person/stories/text-component/text-component.js',
		'js/desktop/person/stories/photo-component/photo-component.js',
		'js/desktop/person/stories/video-component/video-component.js',
		'js/desktop/person/stories/work-component/work-component.js',
		'js/desktop/person/stories/add-component/add-component.js'
	];
	public $depends = [
		'app\assets\desktop\deviser\IndexAsset',
	];
}