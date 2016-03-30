<?php

namespace app\components\assets;

use yii\web\AssetBundle;

class publicFooterAsset extends AssetBundle {
	public $sourcePath;
	public $css;
	public $js;
	public $depends;

	public function init() {

		//TODO: Uncomment when mobile/tablet is implemented
		//$this->sourcePath = '@app/components/public-footer/' . Yii::getAlias('@device');
		$this->sourcePath = '@app/components/public-footer/desktop';
		$this->css = [
			'public-footer.css'
		];
		$this->js = [];
		$this->depends = [];

		parent::init();
	}
}
