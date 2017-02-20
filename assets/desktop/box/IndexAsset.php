<?php
namespace app\assets\desktop\box;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/box/box.js',
		'js/desktop/box/view-boxes.js',
		'js/desktop/box/box-detail.js',
		'js/desktop/box/modal-save-box/modal-save-box.js',
		'js/desktop/box/modal-create-box/modal-create-box.js',
		'js/desktop/box/modal-edit-box/modal-edit-box.js',
		'js/desktop/box/modal-delete-box/modal-delete-box.js',
	];
}
