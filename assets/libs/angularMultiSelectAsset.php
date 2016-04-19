<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularMultiSelectAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-multi-select';
	public $css = [
		YII_ENV_DEV ? 'dist/dev/angular-multi-select.css' : 'dist/prod/angular-multi-select.min.css'
	];
	public $cssOptions = [
		"crossorigin" => "anonymous"
	];
	public $js = YII_ENV_DEV ? [
		'dist/dev/angular-multi-select-constants.js',
		'dist/dev/angular-multi-select-data-converter.js',
		'dist/dev/angular-multi-select-engine.js',
		'dist/dev/angular-multi-select-styles-helper.js',
		'dist/dev/angular-multi-select-utils.js',
		'dist/dev/angular-multi-select.js',
		'dist/dev/angular-multi-select.tpl.js',
		'dist/dev/angular-multi-select-filters.js',
		'dist/dev/angular-multi-select-i18n.js'
	] : [
		'dist/prod/angular-multi-select.min.js'
	];
	public $depends = [
		'app\assets\libs\lokijsAsset'
	];
}
