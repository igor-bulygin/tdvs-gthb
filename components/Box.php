<?php

namespace app\components;

use app\helpers\Utils;
use Yii;
use yii\base\Widget;

class Box extends Widget {

	/** @var  Box $box */
	public $box;

	public function run() {
		return $this->render('Box', [
			'box' => $this->box,
		]);
	}

	public function getViewPath()
	{
		if (Yii::getAlias('@device') != 'desktop') {
			return Utils::join_paths('@app', 'components', 'views', 'Box', Yii::getAlias('@device'));
		}

		return Utils::join_paths('@app', 'components', 'views', 'Box');
	}
}
