<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularBootstrapAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-ui-bootstrap/dist';
	public $css = [
	];
	public $js = [
		'ui-bootstrap.js', //YII_ENV_DEV ? 'ui-bootstrap.js' : 'ui-bootstrap.min.js',
		'ui-bootstrap-tpls.js' //YII_ENV_DEV ? 'ui-bootstrap-tpls.js' : 'ui-bootstrap-tpls.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset',
		'app\assets\libs\angularAnimateAsset'
	];
}
