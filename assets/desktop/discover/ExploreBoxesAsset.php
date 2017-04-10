<?php
namespace app\assets\desktop\discover;

use yii\web\AssetBundle;

class ExploreBoxesAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/discover/boxes/exploreBoxesCtrl.js'
	];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
	];
}