<?php

namespace app\components;

use Yii;
use yii\base\Widget;
use app\helpers\Utils;

class PublicHeaderNavbar extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('PublicHeaderNavbar');
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views', 'PublicHeaderNavbar', Yii::getAlias('@device'));
	}
}
