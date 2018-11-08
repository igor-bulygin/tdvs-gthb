<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class PaymentErrorsAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/payment_errors.css'
	];
	public $js = [
		'js/desktop/admin/payment_errors.js'
	];
	public $depends = [
		'app\assets\desktop\admin\GlobalAsset',
		'app\assets\libs\angularMultiSelectAsset',
		'app\assets\libs\angularBootstrapAsset',
	];
}
