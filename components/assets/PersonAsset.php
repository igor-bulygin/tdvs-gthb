<?php
namespace app\components\assets;
use yii\web\AssetBundle;

class PersonAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/person/person.js',
		'js/desktop/person/person-component.js'
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
		'app\assets\desktop\discover\IndexAsset',
	];
}
