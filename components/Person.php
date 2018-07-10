<?php

namespace app\components;

use app\helpers\Utils;
use Yii;
use yii\base\Widget;

class Person extends Widget {

	/** @var  Person $person */
	public $person;

	public function run() {
		return $this->render('Person', [
			'person' => $this->person,
		]);
	}

	public function getViewPath()
	{
		if (Yii::getAlias('@device') != 'desktop') {
			return Utils::join_paths('@app', 'components', 'views', 'Person', Yii::getAlias('@device'));
		}

		return Utils::join_paths('@app', 'components', 'views', 'Person');
	}
}
