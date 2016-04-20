<?php
namespace app\models;

use Yii;
use app\models\MetricUnit;

class MetricType {
	/*
	 * Be very careful when changing things here. The order is important!
	 */
	const NONE = 0;
	const SIZE = 1;
	const WEIGHT = 2;
	const TXT = [
		"None", "Size", "Weight"
	];

	const UNITS = [
		//NONE
		[],

		//SIZE
		[MetricUnit::MM, MetricUnit::CM, MetricUnit::M, MetricUnit::KM, MetricUnit::IN, MetricUnit::FT, MetricUnit::YD, MetricUnit::MI],

		//WEIGHT
		[MetricUnit::MG, MetricUnit::G, MetricUnit::KG, MetricUnit::OZ, MetricUnit::LB]
	];

	function __construct() {
		Yii::t("app/admin", "None");
		Yii::t("app/admin", "Size");
		Yii::t("app/admin", "Weight");
	}
}
