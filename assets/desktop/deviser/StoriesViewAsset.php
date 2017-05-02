<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class StoriesViewAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
	];
	public $depends = [
		'app\assets\desktop\deviser\IndexStoryAsset',
	];
}