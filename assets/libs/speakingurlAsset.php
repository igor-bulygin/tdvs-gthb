<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class speakingurlAsset extends AssetBundle {
	public $sourcePath = '@npm/speakingurl';
	public $css = [];
	public $js = [
		YII_ENV_DEV ? 'lib/speakingurl.js' : 'speakingurl.min.js'
	];
	public $depends = [];
}
