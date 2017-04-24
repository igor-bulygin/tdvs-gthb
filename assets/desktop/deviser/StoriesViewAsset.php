<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class StoriesViewAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
    	'js/desktop/person/stories/view-stories.js'
    ];
	public $depends = [
        'app\assets\desktop\deviser\IndexStoryAsset',
	];
}