<?php
namespace app\assets;

use yii\web\AssetBundle;

class AppAsset extends AssetBundle {
	public function init() {
		if (\Yii::$app->params['devicedetect']['isMobile']) {
			$this->js[] = "js/mobile/global.js";
			$this->css[] = "css/mobile/global.css";
		} else if(\Yii::$app->params['devicedetect']['isTablet']) {
			$this->js[] = "js/tablet/global.js";
			$this->css[] = "css/tablet/global.css";
		} else {
			$this->js[] = "js/desktop/global.js";
			$this->css[] = "css/desktop/global.css";
		}
	}

	public $basePath = '@webroot';
	public $baseUrl = '@web';
	public $css = [
		'css/fonts.css',
		'css/global.css',
		'css/bootstrap-patches.css',
	];
	public $js = [
		'js/global.js'
	];
	public $depends = [
		'yii\web\YiiAsset',
		'yii\bootstrap\BootstrapAsset',
		'app\assets\libs\jqueryDeparamAsset'
	];
}
