<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularBootstrapDatetimePickerAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-bootstrap-datetimepicker';
	public $css = [
		YII_ENV_DEV ? 'src/css/datetimepicker.css' : 'src/css/datetimepicker.css'
	];
	public $js = [
		YII_ENV_DEV ? 'src/js/datetimepicker.js' : 'src/js/datetimepicker.js'
	];
	public $depends = [
		'app\assets\libs\momentAsset',
		'app\assets\libs\angularAsset'
	];
}
