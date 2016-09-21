<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularMasonryAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-masonry';
	public $js = [
		YII_ENV_DEV ? 'angular-masonry.js' : 'angular-masonry.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
