<?php
namespace app\models;

use Yii;

class MetricUnit {
	//Size/distance
	const MM = "mm";
	const CM = "cm";
	const M = "m";
	const KM = "km";
	const IN = "in";
	const FT = "ft";
	const YD = "yd";
	const MI = "mi";

	//Weight
	const MG = "mg";
	const G = "g";
	const KG = "kg";
	const OZ = "oz";
	const LB = "lb";

	function __construct() {
		Yii::t("app/admin", "mm");
		Yii::t("app/admin", "cm");
		Yii::t("app/admin", "m");
		Yii::t("app/admin", "km");
		Yii::t("app/admin", "in");
		Yii::t("app/admin", "ft");
		Yii::t("app/admin", "yd");
		Yii::t("app/admin", "mi");

		Yii::t("app/admin", "mg");
		Yii::t("app/admin", "g");
		Yii::t("app/admin", "kg");
		Yii::t("app/admin", "oz");
		Yii::t("app/admin", "lb");
	}
}

