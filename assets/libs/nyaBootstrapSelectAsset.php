<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class nyaBootstrapSelectAsset extends AssetBundle {
	public $sourcePath = '@npm/nya-bootstrap-select/dist/';
	public $css = [
		YII_ENV_DEV ? 'css/nya-bs-select.css' : 'css/nya-bs-select.min.css'
	];
	public $js = [
		YII_ENV_DEV ? 'js/nya-bs-select.js' : 'js/nya-bs-select.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}