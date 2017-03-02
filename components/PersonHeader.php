<?php

namespace app\components;

use app\helpers\Utils;
use yii\base\Widget;

class PersonHeader extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('PersonHeader');
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views');
	}
}
