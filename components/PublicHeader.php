<?php

namespace app\components;

use Yii;
use yii\base\Widget;
use app\helpers\Utils;

class PublicHeader extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('PublicHeader');
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views', 'PublicHeader', Yii::getAlias('@device'));
	}
}
