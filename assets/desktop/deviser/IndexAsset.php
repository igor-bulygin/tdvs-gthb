<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/person/person.js',
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
		'app\assets\libs\nyaBootstrapSelectAsset',
		'app\assets\libs\textAngularAsset',
		'app\assets\libs\ngFileUploadAsset',
		'app\assets\libs\angularDragAndDropAsset',
		'app\assets\libs\ngYoutubeEmbedAsset',
		'app\assets\libs\uiCropperAsset',
		'app\assets\libs\angularMasonryAsset',
		'app\assets\libs\angularLocalStorageAsset',
		'app\assets\libs\ngTagsInputAsset',
		'app\assets\libs\angularUiSortableAsset',
	];
}