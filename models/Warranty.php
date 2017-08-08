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
			self::NONE => Yii::t("app/todevise", "None"),
			self::DAYS => Yii::t("app/todevise", "Days"),
			self::WEEKS => Yii::t("app/todevise", "Weeks"),
			self::MONTHS => Yii::t("app/todevise", "Months"),
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
