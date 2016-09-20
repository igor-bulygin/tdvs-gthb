<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/deviser/deviser.js'
	];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\libs\angularAsset',
		'app\assets\libs\angularToastrAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
		'app\assets\libs\nyaBootstrapSelectAsset',
		'app\assets\libs\textAngularAsset',
		'app\assets\libs\ngFileUploadAsset',
		'app\assets\libs\angularDragAndDropAsset',
		'app\assets\libs\ngYoutubeEmbedAsset',
	];
}