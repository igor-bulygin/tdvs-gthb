<?php

namespace app\components;

use app\helpers\Utils;
use yii\base\Widget;

class PersonMenu extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('PersonMenu');
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views');
	}
}
