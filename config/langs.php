<?php

Yii::setAlias('@models', dirname(__DIR__) . '/models');
require_once(Yii::getAlias("@models/Lang.php"));
use app\models\Lang;

//Those are all the available languages.
return array_merge(
	Lang::EN_US,
	Lang::ES_ES,
	Lang::CA_ES
);
