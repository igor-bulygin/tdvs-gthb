<?php

use app\models\Lang;
use app\models\SizeChart;
use app\models\MetricUnit;

class m150620_155610_create_size_charts extends \yii\mongodb\Migration {
	public function up() {
		$en = array_keys(Lang::EN_US)[0];

		$this->createCollection('size_chart');
		$this->createIndex('size_chart', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('size_chart', 'categories');
		$this->createIndex('size_chart', 'enabled');
		$this->createIndex('size_chart', 'type');
		$this->createIndex('size_chart', 'deviser_id');

		$this->insert('size_chart', [
			"short_id" => "11000",
			"name" => [$en => "Jeans for Women"],
			"enabled" => true,
			"type" => SizeChart::TODEVISE,
			"categories" => ["4d2e3", "4e6f9"],
			"metric_unit" => MetricUnit::MM,
			"countries" => ["US", "GB", "EU", "IT", "JP"],
			"columns" => [
				[$en => "Inside leg"],
				[$en => "Rise"],
				[$en => "Leg Opening"],
				[$en => "Outside Leg"],
				[$en => "Waist"],
				[$en => "Hip"],
				[$en => "Cuff Circumference"]
			],
			"values" => [
				[6, 6, 34, 38, 5, 150, 100, 50, 200, 610, 700, 50],
				["6-8", "6-8", "", "", "", 175, 125, 75, 225, 635, 725, 75],
				[8, 8, 36, 40, 7, 200, 150, 100, 250, 660, 750, 100],
				["8-10", "8-10", "", "", "", 225, 175, 125, 275, 685, 775, 125],
				[10, 10, 38, 42, 9, 250, 200, 150, 300, 710, 800, 150],
				["10-12", "10-12", "", "", "", 275, 225, 175, 325, 735, 825, 175],
				[12, 12, 40, 44, 11, 300, 250, 200, 350, 760, 850, 200],
				["12-14", "12-14", "", "", "", 325, 275, 225, 375, 785, 875, 225],
				[14, 14, 42, 46, 13, 350, 300, 250, 400, 810, 900, 250],
				["14-16", "14-16", "", "", "", 375, 325, 275, 425, 835, 925, 275],
				[16, 16, 44, 48, 15, 400, 350, 300, 450, 860, 950, 300],
				[18, 18, "", "", "", 425, 375, 325, 475, 885, 975, 325],
				[20, 20, "", "", "", 450, 400, 350, 500, 910, 1000, 350]
			]
		]);

		$this->insert('size_chart', [
			"short_id" => "12000",
			"name" => [$en => "T-shirts for Men"],
			"enabled" => true,
			"type" => SizeChart::TODEVISE,
			"categories" => ["4x9a5", "4x8b4"],
			"metric_unit" => MetricUnit::MM,
			"countries" => ["US", "EU", "GB", "IT", "JP", "AU", "DE", "DK"],
			"columns" => [
				[$en => "Length"],
				[$en => "Waist"],
				[$en => "Bust"],
				[$en => "Shoulder Width"],
				[$en => "Sleeve Length"],
				[$en => "Sleeve Opening"],
				[$en => "Collar"]
			],
			"values" => [
				[2, 34, 6, 38, 5, 6, 32, 32, 600, 450, 500, 400, 200, 50, 75],
				[4, 36, 8, 40, 7, 8, 34, 34, 625, 475, 525, 425, 225, 75, 100],
				[6, 38, 10, 42, 9, 10, 36, 36, 650, 500, 550, 450, 250, 100, 125],
				[8, 40, 12, 44, 11, 12, 38, 38, 675, 525, 575, 475, 275, 125, 150],
				[10, 42, 14, 46, 13, 14, 40, 40, 700, 550, 600, 500, 300, 125, 175],
				[12, 44, 16, 48, 15, 16, 42, 42, 725, 575, 625, 525, 325, 125, 175],
				[14, 46, 18, 50, 17, 18, 44, 44, 750, 600, 650, 550, 350, 125, 175],
				[16, 48, 20, 52, 19, 20, 46, 46, 775, 625, 675, 550, 350, 150, 175]
			]
		]);

	}

	public function down() {
		$this->dropAllIndexes('size_chart');
		$this->dropCollection('size_chart');
	}
}
