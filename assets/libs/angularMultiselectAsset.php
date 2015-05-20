<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularMultiselectAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-multi-select';
	public $css = [
		//TODO: min
		'angular-multi-select.css'
	];
	public $js = [
		//TODO: min
		'angular-multi-select.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
