<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularUiValidateAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-ui-validate/dist';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'validate.js' : 'validate.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
