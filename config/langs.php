<?php

Yii::setAlias('@models', dirname(__DIR__) . '/models');
require_once(Yii::getAlias("@models/Lang.php"));
use app\models\Lang;

//Those are all the available languages.
return Lang::getAvailableLanguages();
