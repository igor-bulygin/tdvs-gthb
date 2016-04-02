<?php

use app\helpers\Utils;

require Utils::join_paths(Yii::getAlias("@app"), "components", "views", "LeftMenu", "desktop", basename(__FILE__));
