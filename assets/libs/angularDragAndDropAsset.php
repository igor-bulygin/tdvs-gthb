<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularDragAndDropAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-drag-and-drop-lists';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'angular-drag-and-drop-lists.js' : 'angular-drag-and-drop-lists.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}