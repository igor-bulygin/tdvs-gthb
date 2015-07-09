<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularImgDlAsset extends AssetBundle {
	public $sourcePath = YII_ENV_DEV ? '@npm/angular-img-dl/src' : '@npm/angular-img-dl/dist';
	public $css = [];
	public $js = [
		YII_ENV_DEV ? 'angular-img-dl.js' : 'angular-img-dl.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
