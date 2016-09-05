<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class EditVideosAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/deviser/edit-video.js',
	];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\libs\angularAsset',
		'app\assets\libs\angularToastrAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\api\ApiAsset',
		'app\assets\libs\angularDragAndDropAsset',
		'app\assets\libs\utilAsset',
		'app\assets\libs\ngYoutubeEmbedAsset'
	];
}