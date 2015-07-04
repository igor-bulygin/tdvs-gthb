<?php

use app\models\Lang;
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

		$this->insert('size_chart', [
			"short_id" => "10000",
			"name" => [$en => "Pant sizes"],
			"enabled" => true,
			"categories" => ["30000", "50000"],
			"metric_unit" => MetricUnit::MM,
			"countries" => ["ES", "US"],
			"columns" => [
				[$en => "Inside leg"],
				[$en => "Rise"],
				[$en => "Waist"]
			],
			"values" => [
				[1, 2, 3, 4, 5],
				[1, 2, 3, 4, 5],
				[1, 2, 3, 4, 5]
			]
		]);

	}

	public function down() {
		$this->dropAllIndexes('size_chart');
		$this->dropCollection('size_chart');
	}
}
