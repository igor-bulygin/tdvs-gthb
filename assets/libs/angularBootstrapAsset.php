<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularBootstrapAsset extends AssetBundle {
	public $sourcePath = '@bower/angular-bootstrap';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'ui-bootstrap.js' : 'ui-bootstrap.min.js',
		YII_ENV_DEV ? 'ui-bootstrap-tpls.js' : 'ui-bootstrap-tpls.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
