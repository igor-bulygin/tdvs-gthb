<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularMultiselectAsset extends AssetBundle {
	public $sourcePath = '@bower/isteven-angular-multiselect';
	public $css = [
		'isteven-multi-select.css'
	];
	public $js = [
		'isteven-multi-select.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}
