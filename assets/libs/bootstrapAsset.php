<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class bootstrapAsset extends AssetBundle {
	public $sourcePath = '@bower/bootstrap/dist';
	public $css = [
		YII_ENV_DEV ? 'css/bootstrap.css' : 'css/bootstrap.min.css'
	];
	public $js = [
		YII_ENV_DEV ? 'js/bootstrap.js' : 'js/bootstrap.min.js'
	];
	public $depends = [];
}
