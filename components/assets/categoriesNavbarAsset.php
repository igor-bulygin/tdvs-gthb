<?php

namespace app\components\assets;

use yii\web\AssetBundle;

class categoriesNavbarAsset extends AssetBundle {
	public $sourcePath = '@app/components/categories-navbar';
	public $css = [
		'categories-navbar.css'
	];
	public $js = [];
	public $depends = [];
}
