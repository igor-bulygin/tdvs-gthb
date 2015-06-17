<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularSortableViewAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-sortable-view/src';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'angular-sortable-view.js' : 'angular-sortable-view.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
