<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class BannersAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/public-2/application.css',
		'css/desktop/public-2/bootstrap-select.min.css',
	];
	public $js = [
		'js/api/bannerDataService.js',
		'js/desktop/admin/banners.js'
	];
	public $depends = [
		'app\assets\desktop\admin\GlobalAsset',
		'app\assets\libs\nyaBootstrapSelectAsset',
		'app\assets\libs\angularUiSortableAsset'
	];
}
