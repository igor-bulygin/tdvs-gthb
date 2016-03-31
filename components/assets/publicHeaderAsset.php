<?php

namespace app\components\assets;

use Yii;
use yii\web\AssetBundle;

class publicHeaderAsset extends AssetBundle {

	public $sourcePath;
	public $css;
	public $js;
	public $depends;

	public function init() {

		//TODO: Uncomment when mobile/tablet is implemented
		//$this->sourcePath = '@app/components/public-header/' . Yii::getAlias('@device');
		$this->sourcePath = '@app/components/public-header/desktop';
		$this->css = [
			'public-header.css'
		];
		$this->js = [];
		$this->depends = [];

		parent::init();
	}
}
