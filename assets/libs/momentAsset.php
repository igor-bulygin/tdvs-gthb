<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class momentAsset extends AssetBundle {
	public $sourcePath = '@npm/moment';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'moment.js' : 'min/moment.min.js'
	];
	public $depends = [
	];
}
