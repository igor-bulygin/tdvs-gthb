<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class ContactAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/pub/statictext.css',
		'css/desktop/pub/contact.css'
	];
	public $js = [
		'js/desktop/public-2/contact.js'
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
	];
}
