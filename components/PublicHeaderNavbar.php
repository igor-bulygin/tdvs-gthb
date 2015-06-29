<?php

namespace app\components;

use Yii;
use yii\base\Widget;

class PublicHeaderNavbar extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('PublicHeaderNavbar');
	}
}