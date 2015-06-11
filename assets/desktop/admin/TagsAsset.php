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
		'app\assets\api\ApiAsset',
		'app\assets\libs\angularMultiSelectAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\libs\jsonpathAsset',
		'app\assets\desktop\admin\GlobalAsset'
	];
}
