<?php

namespace app\components;

use app\helpers\Utils;
use yii\base\Widget;

class MakeProfilePublic extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('MakeProfilePublic');
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views');
	}
}
