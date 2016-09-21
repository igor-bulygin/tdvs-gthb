<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class macyAsset extends AssetBundle {
	public $sourcePath = '@npm/macy/dist';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'macy.js' : 'macy.min.js'
	];
	public $depends = [
	];
}
