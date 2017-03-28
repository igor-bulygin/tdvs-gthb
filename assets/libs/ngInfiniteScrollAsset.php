<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class ngInfiniteScrollAsset extends AssetBundle {
	public $sourcePath = '@npm/ng-infinite-scroll/build/';
	public $css = [];
	public $js = [
		YII_ENV_DEV ? 'ng-infinite-scroll.js' : 'ng-infinite-scroll.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}