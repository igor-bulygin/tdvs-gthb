<?php
namespace app\assets\desktop\cart;

use yii\web\AssetBundle;

class OrderAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
	];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
	];
}