<?php

namespace app\components;

use app\models\Category;
use Yii;
use yii\base\Widget;
use app\helpers\Utils;

class DeviserAdminHeader extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('DeviserAdminHeader');
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views');
	}
}
