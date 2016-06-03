<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class TermsAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/pub/terms.css'
	];
	public $js = [
		'js/desktop/pub/terms.js'
	];
	public $depends = [
		'app\assets\desktop\pub\GlobalAsset',
		'app\assets\libs\socialShareKitAsset',
		'app\assets\libs\justifiedGalleryAsset'
	];
}
