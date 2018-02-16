<?php
namespace app\assets\desktop\chat;

use yii\web\AssetBundle;

class GlobalAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/chat/chats.js'
	];
	public $depends = [
		'app\assets\desktop\GlobalAsset',
		'app\assets\desktop\chat\IndexAsset',
	];
}