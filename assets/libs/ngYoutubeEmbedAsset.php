<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class ngYoutubeEmbedAsset extends AssetBundle {
	public $sourcePath = '@npm/ng-youtube-embed/';	
	public $js = [
		YII_ENV_DEV ? 'src/ng-youtube-embed.js' : 'build/ng-youtube-embed.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset'
	];
}