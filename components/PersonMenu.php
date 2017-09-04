<?php

namespace app\components;

use app\helpers\Utils;
use Yii;
use yii\base\Widget;

class PersonMenu extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('PersonMenu');
	}

	public function getViewPath() {
		if (Yii::getAlias('@device') != 'desktop') {
			return Utils::join_paths('@app', 'components', 'views', 'PersonMenu', Yii::getAlias('@device'));
		}
		return Utils::join_paths('@app', 'components', 'views', 'PersonMenu');
	}
}
