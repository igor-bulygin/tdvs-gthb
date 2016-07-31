<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class BecomeAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/pub/statictext.css',
		'css/desktop/pub/contact.css',
		'css/desktop/pub/become.css'
	];
	public $js = [
		'js/desktop/pub/become.js'
	];
	public $depends = [
		'app\assets\desktop\pub\GlobalAsset',
	];
}
