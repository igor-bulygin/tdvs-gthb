<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularToastrAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-toastr/dist';
	public $css = [
		YII_ENV_DEV ? 'angular-toastr.css' : 'angular-toastr.min.css'
	];
	public $js = [
		YII_ENV_DEV ? 'angular-toastr.js' : 'angular-toastr.min.js',
		YII_ENV_DEV ? 'angular-toastr.tpls.js' : 'angular-toastr.tpls.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAnimateAsset'
	];
}
