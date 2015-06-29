<?php

namespace app\components\assets;

use yii\web\AssetBundle;

class publicFooterAsset extends AssetBundle {
	public $sourcePath = '@app/components/public-footer';
	public $css = [
		'public-footer.css'
	];
	public $js = [];
	public $depends = [];
}
