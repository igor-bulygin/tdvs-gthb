<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class TagAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/tag.css'
	];
	public $js = [
		'js/desktop/admin/tag.js'
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\jsonpathAsset',
		'app\assets\libs\angularUiUtilsAsset',
		'app\assets\libs\angularMultiSelectAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\libs\angularSortableViewAsset',
		'app\assets\desktop\admin\GlobalAsset'
	];
}
