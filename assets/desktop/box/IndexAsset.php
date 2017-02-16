<?php
namespace app\assets\desktop\box;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/box/box.js',
		'js/desktop/box/modal-save/modal-save.js',
	];
}
