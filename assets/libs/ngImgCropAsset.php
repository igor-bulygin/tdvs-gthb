<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class ngImgCropAsset extends AssetBundle {
	public $sourcePath = '@bower/ngImgCrop/compile';
	public $css = [
		YII_ENV_DEV ? 'unminified/ng-img-crop.css' : 'minified/ng-img-crop.css'
	];
	public $js = [
		YII_ENV_DEV ? 'unminified/ng-img-crop.js' : 'minified/ng-img-crop.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
