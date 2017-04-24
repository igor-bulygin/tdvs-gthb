<?php
namespace app\assets\desktop\pub;

use yii\web\AssetBundle;

class StoryDetailAsset extends AssetBundle {
    public $basePath = '@webroot';
    public $baseUrl = '@web';
    public $css = [];
    public $js = [
    ];
	public $depends = [
		'app\assets\desktop\pub\PublicCommonAsset',
	];
}
