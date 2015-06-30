<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class EditInfoAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/deviser/edit-info.css'
	];
	public $js = [
		'js/desktop/deviser/edit-info.js'
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\angularMultiSelectAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\libs\ngFileUploadAsset',
		'app\assets\libs\ngImgCropAsset',
		'app\assets\desktop\deviser\GlobalAsset'
	];
}
