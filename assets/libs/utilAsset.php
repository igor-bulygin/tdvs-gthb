<?php
namespace app\assets\libs;

use yii\web\AssetBundle;

class utilAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/util/util.js',
		'js/util/treeService.js',
		'js/util/comparator.js',
		'js/util/form-messages/form-messages.js',
		'js/util/form-errors/form-errors.js',
		'js/util/modal-crop/modal-crop.js'
	];
	
	public $depends = [
		'app\assets\libs\angularMessagesAsset',
		'app\assets\libs\ngImgCropAsset'
	];
}