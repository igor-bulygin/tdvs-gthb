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
		'js/util/localStorageUtilService.js',
		'js/util/treeService.js',
		'js/util/comparator.js',
		'js/util/form-messages/form-messages.js',
		'js/util/form-errors/form-errors.js',
		'js/util/modal-crop/modal-crop.js',
		'js/util/modal-confirm-leave/modal-confirm-leave.js',
		'js/util/modal-crop-description/modal-crop-description.js',
		'js/util/product-card/product-card.js',
		'js/util/contenteditable.js',
		'js/util/dragndropService.js',
		'js/util/image-hover-buttons/image-hover-buttons.js',
		'js/util/modal-love-post/modal-love-post.js',
		'js/util/modal-signup-loved/modal-signup-loved.js',
		'js/util/onPressEnter.js',
		'js/util/modal-info/modal-info.js',
		'js/util/modal-acept-reject/modal-acept-reject.js',
		'js/util/modal-delete-product/modal-delete-product.js'
	];
	
	public $depends = [
		'app\assets\libs\angularMessagesAsset',
		'app\assets\libs\uiCropperAsset',
		'app\assets\libs\angularLocalStorageAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\libs\ngInfiniteScrollAsset'
	];
}