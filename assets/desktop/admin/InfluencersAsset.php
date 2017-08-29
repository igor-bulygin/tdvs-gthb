<?php
namespace app\assets\desktop\admin;

use yii\web\AssetBundle;

class InfluencersAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/desktop/admin/influencers.css'
	];
	public $js = [
		'js/desktop/admin/influencers.js'
	];
	public $depends = [
		'app\assets\desktop\admin\GlobalAsset',
		'app\assets\libs\angularMultiSelectAsset',
		'app\assets\libs\angularBootstrapAsset',
	];
}
