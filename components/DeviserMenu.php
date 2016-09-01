<?php

namespace app\components;

use app\models\Category;
use Yii;
use yii\base\Widget;
use app\helpers\Utils;

class DeviserMenu extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('DeviserMenu');
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views');
	}
}
