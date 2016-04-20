<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class SizeChartAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/sizechart.css'
	];
	public $js = [
		'js/desktop/admin/sizechart.js'
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\angularUiValidateAsset',
		'app\assets\libs\angularMultiSelectAsset',
		'app\assets\libs\angularUnitConverterAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\libs\angularSortableViewAsset',
		'app\assets\desktop\admin\GlobalAsset'
	];
}
