<?php

namespace app\components;

use app\helpers\Utils;
use Yii;
use yii\base\Widget;

class SettingsHeader extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('SettingsHeader');
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views');
	}
}
