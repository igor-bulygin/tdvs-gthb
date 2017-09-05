<?php

namespace app\components;

use app\helpers\Utils;
use Yii;
use yii\base\Widget;

class MakeProfilePublic extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('MakeProfilePublic');
	}

	public function getViewPath() {
		if (Yii::getAlias('@device') != 'desktop') {
			return Utils::join_paths('@app', 'components', 'views', 'MakeProfilePublic', Yii::getAlias('@device'));
		}
		return Utils::join_paths('@app', 'components', 'views', 'MakeProfilePublic');
	}
}
