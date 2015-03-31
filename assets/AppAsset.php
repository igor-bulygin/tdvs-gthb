<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
	public function init()
	{
		if (\Yii::$app->params['devicedetect']['isMobile']) {
			$this->css[] = "css/mobile/global.css";
		} else if(\Yii::$app->params['devicedetect']['isTablet']) {
			$this->css[] = "css/tablet/global.css";
		} else {
			$this->css[] = "css/desktop/global.css";
		}
	}

	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/global.css',
		'css/bootstrap-patches.css',
	];
	public $js = [
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
	];
}
