<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularUiRouteAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-ui-router';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'release/angular-route.js' : 'release/angular-route.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}