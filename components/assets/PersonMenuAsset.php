<?php
namespace app\components\assets;
use yii\web\AssetBundle;

class PersonMenuAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/person/person-menu.js',
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
	];
}
