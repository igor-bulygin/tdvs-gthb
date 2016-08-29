<?php
namespace app\assets\desktop\deviser;

use yii\web\AssetBundle;

class EditPressAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/deviser/index.css',
	];
	public $js = [
		'js/desktop/deviser/edit-press.js',
		'js/util/util.js',
		'js/api/deviserDataService.js'
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\desktop\deviser\GlobalAsset'
	];
}