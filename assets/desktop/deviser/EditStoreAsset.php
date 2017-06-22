<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class EditStoreAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/person/edit-store.js',
	];
	public $depends = [
		'app\assets\desktop\deviser\IndexAsset',
	];
}