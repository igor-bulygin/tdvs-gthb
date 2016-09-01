<?php

namespace app\components;

use app\models\Category;
use Yii;
use yii\base\Widget;
use app\helpers\Utils;

class DeviserHeader extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('DeviserHeader');
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views');
	}
}
