<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class ngTagsInputAsset extends AssetBundle {
	public $sourcePath = '@npm/ng-tags-input/build/';
	public $js = [
		YII_ENV_DEV ? 'ng-tags-input.js' : 'ng-tags-input.min.js'
	];
	public $css = [
		YII_ENV_DEV ? 'ng-tags-input.css' : 'ng-tags-input.min.css'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}