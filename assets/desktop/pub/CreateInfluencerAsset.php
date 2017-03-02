<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class CreateInfluencerAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/public-2/create-influencer-account.js'
	];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
		'app\assets\libs\angularAsset',
		'app\assets\libs\angularBootstrapAsset',
		'app\assets\libs\angularToastrAsset',
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset'
	];
}