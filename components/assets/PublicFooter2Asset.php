<?php
namespace app\components\assets;

use yii\web\AssetBundle;

class PublicFooter2Asset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [];
	public $js = [
		'js/desktop/public-2/footer.js',
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset',
	];
}
