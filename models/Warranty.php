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
			self::NONE => Yii::t("app/public", 'NO_WARRANTY'),
			self::DAYS => Yii::t("app/public", 'DAYS'),
			self::WEEKS => Yii::t("app/public", 'WEEKS'),
			self::MONTHS => Yii::t("app/public", 'MONTHS'),
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
