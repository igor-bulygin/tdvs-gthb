<?php
namespace app\models;

use Yii;
use app\models\MetricUnit;

class PaperType
{
	const FINE_ART_PAPER = 1;

	const CANVAS = 2;

	const TXT = [
		self::FINE_ART_PAPER => 'Fine art paper',
		self::CANVAS => 'Canvas',
	];

	public static function getSerialized()
	{
		$items = [
			PaperType::FINE_ART_PAPER,
			PaperType::CANVAS,
		];
		$objects = [];
		$texts = PaperType::TXT;
		foreach ($items as $item) {
			$object = new \stdClass();
			$object->type = $item;
			$object->name = $texts[$item];
			$objects[] = $object;
		}
		return $objects;
	}
}