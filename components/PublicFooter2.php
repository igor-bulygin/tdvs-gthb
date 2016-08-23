<?php

namespace app\components;

use Yii;
use yii\base\Widget;
use app\helpers\Utils;

class PublicFooter2 extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('PublicFooter2');
	}

	public function getViewPath() {
		return Utils::join_paths('@app', 'components', 'views', 'PublicFooter2');
	}
}
