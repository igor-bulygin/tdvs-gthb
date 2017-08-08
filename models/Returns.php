<?php
namespace app\models;

use Yii;

class Returns {
	const NONE = 0;
	const DAYS = 1;

	public static function getDescription($code)
	{
		$descs = [
			self::NONE => Yii::t("app/todevise", "None"),
			self::DAYS => Yii::t("app/todevise", "Days"),
		];
		return $descs[$code];
	}

	public static function getAvailableValues()
	{
		return [
			static::NONE,
			static::DAYS,
		];
	}
}
