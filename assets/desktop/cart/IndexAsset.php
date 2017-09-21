<?php
namespace app\assets\desktop\cart;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/cart/cart.js',
		'js/desktop/cart/cartService.js'
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\libs\nyaBootstrapSelectAsset',
		'app\assets\libs\textAngularAsset',
		'app\assets\libs\ngFileUploadAsset',
		'app\assets\libs\angularDragAndDropAsset',
		'app\assets\libs\uiCropperAsset',
		'app\assets\libs\ngTagsInputAsset',
		'app\assets\libs\angularLocalStorageAsset',
		'app\assets\libs\angularBootstrapDatetimePickerAsset',
		'app\assets\libs\angularXEditableAsset',
		'app\assets\libs\angularUiSortableAsset',
		'app\assets\libs\angularLocaleAsset',
	];
}