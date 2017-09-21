<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
	];
	public $js = [
		'js/desktop/person/complete-profile.js',
		'js/desktop/person/view-store.js',
		'js/desktop/person/edit-store.js',
		'js/desktop/person/edit-header.js',
		'js/desktop/person/edit-about/edit-about.js',
		'js/desktop/person/edit-faq.js',
		'js/desktop/person/edit-press/edit-press.js',
		'js/desktop/person/edit-videos/edit-videos.js',
		'js/desktop/person/make-profile-public.js',
		'js/desktop/person/person-not-public.js',
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
		'app\assets\desktop\deviser\IndexAsset',
	];
}