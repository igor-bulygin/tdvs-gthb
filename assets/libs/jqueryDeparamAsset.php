<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class jqueryDeparamAsset extends AssetBundle
{
	public $sourcePath = '@npm/jquery-deparam';
	public $css = [
	];
	public $js = [
		'jquery-deparam.js'
	];
	public $depends = [
		'yii\web\JqueryAsset',
	];
}
