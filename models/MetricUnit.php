<?php
namespace app\models;

use Yii;

class MetricUnit {
	const NONE = 0;
	const SIZE = 1;
	const WEIGHT = 2;
	const TXT = [
		"None", "Size", "Weight"
	];

	function __construct() {
		Yii::t("app/admin", "None");
		Yii::t("app/admin", "Size");
		Yii::t("app/admin", "Weight");
	}
}