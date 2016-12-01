<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularXEditable extends AssetBundle {
	public $sourcePath = '@npm/angular-xeditable/dist';
	public $css = [
		YII_ENV_DEV ? 'css/xeditable.css' : 'css/xeditable.min.css'
		];
	public $js = [
		YII_ENV_DEV ? 'js/xeditable.js' : 'js/xeditable.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
