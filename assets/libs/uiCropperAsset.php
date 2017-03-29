<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class uiCropperAsset extends AssetBundle {
	public $sourcePath = '@npm/ui-cropper/compile';
	public $css = [
		YII_ENV_DEV ? 'unminified/ui-cropper.css' : 'minified/ui-cropper.css'
	];
	public $js = [
		YII_ENV_DEV ? 'unminified/ui-cropper.js' : 'minified/ui-cropper.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
