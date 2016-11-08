<?php
namespace app\assets\desktop\product;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/product/product.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset',
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\libs\angularToastrAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
		'app\assets\libs\nyaBootstrapSelectAsset',
		'app\assets\libs\textAngularAsset',
		'app\assets\libs\ngFileUploadAsset',
		'app\assets\libs\angularDragAndDropAsset',
		'app\assets\libs\ngImgCropAsset',
		'app\assets\libs\ngTagsInputAsset',
		'app\assets\libs\angularLocalStorageAsset',
		'app\assets\libs\angularBootstrapDatetimePickerAsset'
	];
}