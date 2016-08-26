<?php
namespace app\models;

use Yii;

class Warranty {
	const NONE = 0;
	const DAYS = 1;
	const WEEKS = 2;
	const MONTHS = 3;

	public static function getDescription($code)
	{
		$descs = [
			self::NONE => Yii::t("app/warranties", "None"),
			self::DAYS => Yii::t("app/warranties", "Days"),
			self::WEEKS => Yii::t("app/warranties", "Weeks"),
			self::MONTHS => Yii::t("app/warranties", "Months"),
		];
		return $descs[$code];
	}
}
