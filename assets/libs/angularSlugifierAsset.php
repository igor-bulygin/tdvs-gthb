<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularSlugifierAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-slugifier';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'src/angular-slugifier.js' : 'dist/angular-slugifier.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset',
		'app\assets\libs\speakingurlAsset'
	];
}
