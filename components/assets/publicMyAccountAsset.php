<?php

namespace app\components\assets;

use Yii;
use yii\web\AssetBundle;

class publicMyAccountAsset extends AssetBundle {

	public $sourcePath;
	public $css;
	public $js;
	public $depends;

	public function init() {

		//TODO: Uncomment when mobile/tablet is implemented
		//$this->sourcePath = '@app/components/public-my-account/' . Yii::getAlias('@device');
		$this->sourcePath = '@app/components/public-my-account/desktop';
		$this->css = [
			'public-my-account.css'
		];
		$this->js = [];
		$this->depends = [];

		parent::init();
	}
}
