<?php
namespace app\models;

use Yii;

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

	public static function getSerialized()
	{
		$object = new \stdClass();
		$units = MetricType::UNITS;
		$object->size = $units[MetricType::SIZE];
		$object->weight = $units[MetricType::WEIGHT];

		return $object;
	}

	/**
	 * Returns all available dimensions
	 *
	 * @return array
	 */
	public static function getAvailableDimensions() {
		$units = self::UNITS;
		return $units[self::SIZE];
	}

	/**
	 * Returns all available weights
	 *
	 * @return array
	 */
	public static function getAvailableWeights() {
		$units = self::UNITS;
		return $units[self::WEIGHT];
	}
}
