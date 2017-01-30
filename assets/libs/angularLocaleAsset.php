<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularLocaleAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-i18n';
	public $css = [
	];
	public $js = [
		'angular-locale_en-us.js',
	];
	public $depends = [
		'app\assets\libs\angularAsset',
	];
}
