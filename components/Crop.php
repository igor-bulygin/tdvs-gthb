<?php

namespace app\components;

use Yii;
use yii\base\Widget;

class Crop extends Widget {
	/**
	 * @inheritdoc
	 */

	public function run() {
		return $this->render('Crop');
	}
}