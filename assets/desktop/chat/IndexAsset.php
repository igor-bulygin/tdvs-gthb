<?php
namespace app\assets\desktop\chat;

use yii\web\AssetBundle;

class IndexAsset extends AssetBundle {
	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $js = [
		'js/desktop/chat/chat.js',
		'js/desktop/chat/chats.js',
	];
	public $depends = [
		'app\assets\api\ApiAsset',
		'app\assets\libs\utilAsset'
	];
}