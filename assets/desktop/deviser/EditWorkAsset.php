<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class EditWorkAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/deviser/edit-work.css'
	];
	public $js = [
		'js/desktop/deviser/edit-work.js'
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\angularMultiSelectAsset',
		'app\assets\libs\angularUnitConverterAsset',
		'app\assets\libs\angularImgDlAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\libs\angularBootstrapDatetimePickerAsset',
		'app\assets\libs\ngFileUploadAsset',
		'app\assets\desktop\deviser\GlobalAsset'
	];
}
