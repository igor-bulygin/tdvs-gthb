<?php
namespace app\models;

class Returns {
	const NONE = 0;
	const DAYS = 1;

	public static function getAvailableValues()
	{
		return [
			static::NONE,
			static::DAYS,
		];
	}
}
