<?php

namespace app\components;

use Yii;
use yii\base\Widget;
use app\helpers\Utils;

class PublicFooter extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('PublicFooter');
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views', 'PublicFooter', Yii::getAlias('@device'));
	}
}
