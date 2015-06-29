<?php

namespace app\components\assets;

use yii\web\AssetBundle;

class publicHeaderNavbarAsset extends AssetBundle {
	public $sourcePath = '@app/components/public-header-navbar';
	public $css = [
		'public-header-navbar.css'
	];
	public $js = [];
	public $depends = [];
}
