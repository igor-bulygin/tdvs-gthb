<?php

namespace app\components\assets;

use yii\web\AssetBundle;

class leftMenuAsset extends AssetBundle {
	public $sourcePath;
	public $css;
	public $js;
	public $depends;

	public function init() {

		//TODO: Uncomment when mobile/tablet is implemented
		//$this->sourcePath = '@app/components/left-menu/' . Yii::getAlias('@device');
		$this->sourcePath = '@app/components/left-menu/desktop';
		$this->css = [
			'left-menu.css'
		];
		$this->js = [];
		$this->depends = [];

		parent::init();
	}
}
