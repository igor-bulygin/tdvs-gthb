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
		'app\assets\desktop\admin\GlobalAsset',
		'app\assets\libs\angularUiValidateAsset',
		'app\assets\libs\angularMultiSelectAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\libs\angularSortableViewAsset',
	];
}
