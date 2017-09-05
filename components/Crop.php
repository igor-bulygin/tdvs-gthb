<?php

namespace app\components;

use Yii;
use yii\base\Widget;

class Crop extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('Crop');
	}

	public function getViewPath() {
		if (Yii::getAlias('@device') != 'desktop') {
			return Utils::join_paths('@app', 'components', 'views', 'Crop', Yii::getAlias('@device'));
		}
		return Utils::join_paths('@app', 'components', 'views', 'Crop');
	}
}