<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularUiUtilsAsset extends AssetBundle {
	public $sourcePath = '@bower/angular-ui-utils';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'ui-utils-ieshiv.js' : 'ui-utils-ieshiv.min.js',
		YII_ENV_DEV ? 'ui-utils.js' : 'ui-utils.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
