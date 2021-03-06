<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class TagsAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/tags.css'
	];
	public $js = [
		'js/desktop/admin/tags.js'
	];
	public $depends = [
		'app\assets\desktop\admin\GlobalAsset',
		'app\assets\libs\angularMultiSelectAsset',
		'app\assets\libs\angularBootstrapAsset',
	];
}
