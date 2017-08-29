<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class DevisersAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/devisers.css'
	];
	public $js = [
		'js/desktop/admin/devisers.js'
	];
	public $depends = [
		'app\assets\desktop\admin\GlobalAsset',
		'app\assets\libs\angularMultiSelectAsset',
		'app\assets\libs\angularBootstrapAsset',
	];
}
