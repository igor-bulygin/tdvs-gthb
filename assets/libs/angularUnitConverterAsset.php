<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularUnitConverterAsset extends AssetBundle {
	public $sourcePath = YII_ENV_DEV ? '@npm/angular-unit-converter/src' : '@npm/angular-unit-converter/dist';
	public $css = [];
	public $js = [
		YII_ENV_DEV ? 'angular-unit-converter.js' : 'angular-unit-converter.min.js'
	];
	public $depends = [
		'app\assets\libs\decimaljsAsset'
	];
}
