<?php

namespace app\assets\libs;

use yii\web\AssetBundle;

class angularUiSortableAsset extends AssetBundle {
	public $sourcePath = '@npm/angular-ui-sortable/dist';
	public $css = [
	];
	public $js = [
		YII_ENV_DEV ? 'sortable.js' : 'sortable.min.js'
	];
	public $depends = [
		'app\assets\libs\angularAsset',
		'app\assets\libs\jqueryUiAsset'
	];
}
