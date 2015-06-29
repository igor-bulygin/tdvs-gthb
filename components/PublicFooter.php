<?php

namespace app\components;

use Yii;
use yii\base\Widget;

class PublicFooter extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('PublicFooter');
	}
}