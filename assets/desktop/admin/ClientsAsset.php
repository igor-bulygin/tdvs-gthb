<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class ClientsAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/clients.css'
	];
	public $js = [
		'js/desktop/admin/clients.js'
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\angularMultiSelectAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\desktop\admin\GlobalAsset'
	];
}
