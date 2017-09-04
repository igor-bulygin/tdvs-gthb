<?php

namespace app\components;

use app\helpers\Utils;
use Yii;
use yii\base\Widget;

class PersonHeader extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('PersonHeader');
	}

	public function getViewPath() {
		if (Yii::getAlias('@device') != 'desktop') {
			return Utils::join_paths('@app', 'components', 'views', 'PersonHeader', Yii::getAlias('@device'));
		}
		return Utils::join_paths('@app', 'components', 'views', 'PersonHeader');
	}
}
