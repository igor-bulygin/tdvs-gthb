<?php

namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle
{
	public function init()
	{
		$dd = \Yii::$app->devicedetect;
		if ($dd->isMobile()) {
			$this->css[] = "css/mobile/global.css";
		} else if($dd->isTablet()) {
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
