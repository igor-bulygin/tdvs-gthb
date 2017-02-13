<?php
namespace app\assets\libs;

use yii\web\AssetBundle;

class utilAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		//'js/util/gplaces/gplaces.css'
	];
	public $js = [
		'js/util/util.js',
		'js/util/treeService.js',
		'js/util/comparator.js',
		'js/util/form-messages/form-messages.js',
		'js/util/form-errors/form-errors.js',
		'js/util/modal-crop/modal-crop.js',
		'js/util/modal-confirm-leave/modal-confirm-leave.js',
		'js/util/modal-crop-description/modal-crop-description.js',
		'js/util/contenteditable.js',
		'js/util/dragndropService.js',
		'js/util/image-hover-buttons/image-hover-buttons.js',
		'js/util/modal-signup-loved/modal-signup-loved.js',
	];
	
	public $depends = [
		'app\assets\libs\angularMessagesAsset',
		'app\assets\libs\ngImgCropAsset',
		'app\assets\libs\angularLocalStorageAsset',
		'app\assets\libs\angularBootstrapAsset',
	];
}