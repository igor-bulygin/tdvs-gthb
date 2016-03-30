<?php

use app\helpers\Utils;

require Utils::join_paths(Yii::getAlias("@app"), "components", "views", "PublicHeaderNavbar", "desktop", basename(__FILE__));
