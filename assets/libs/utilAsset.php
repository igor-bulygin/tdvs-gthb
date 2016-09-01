<?php
namespace app\assets\libs;

use yii\web\AssetBundle;

class utilAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/util/util.js',
		'js/util/treeService.js'
	];
}