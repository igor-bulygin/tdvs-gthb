<?php
namespace app\components\assets;

use yii\web\AssetBundle;

class cropAsset extends AssetBundle {
	public $css = [];
	public $js = [];
	public $depends = [
		'app\assets\libs\ngImgCropAsset'
	];
}
