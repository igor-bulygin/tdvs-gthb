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
			self::NONE => Yii::t("app/public", "None"),
			self::DAYS => Yii::t("app/public", "Days"),
			self::WEEKS => Yii::t("app/public", "Weeks"),
			self::MONTHS => Yii::t("app/public", "Months"),
		];
		return $descs[$code];
	}

	public static function getAvailableValues()
	{
		return [
			static::NONE,
			static::DAYS,
			static::WEEKS,
			static::MONTHS,
		];
	}
}
