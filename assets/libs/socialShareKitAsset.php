<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class socialShareKitAsset extends AssetBundle
{
	public $sourcePath = '@npm/social-share-kit';
	public $css = [
		'dist/css/social-share-kit.css'
	];
	public $js = [
		YII_ENV_DEV ? 'dist/js/social-share-kit.js' : 'dist/js/social-share-kit.min.js'
	];
	public $depends = [
	];
}
