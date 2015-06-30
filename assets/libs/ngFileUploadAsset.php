<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class ngFileUploadAsset extends AssetBundle {
	public $sourcePath = '@npm/ng-file-upload/dist';
	public $css = [];
	public $js = [
		YII_ENV_DEV ? 'ng-file-upload.js' : 'ng-file-upload.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
