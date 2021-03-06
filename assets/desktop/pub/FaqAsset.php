<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class FaqAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/pub/statictext.css',
		'css/desktop/pub/faq.css'
	];
	public $js = [
		'js/desktop/pub/faq.js'
	];
	public $depends = [
		'app\assets\desktop\pub\GlobalAsset',
	];
}
