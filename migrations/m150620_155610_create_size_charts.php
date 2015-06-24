<?php

class m150620_155610_create_size_charts extends \yii\mongodb\Migration {
	public function up() {
		$this->createCollection('size_chart');
		$this->createIndex('size_chart', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('size_chart', 'categories');
		$this->createIndex('size_chart', 'enabled');

		$this->insert('size_chart', [
			"short_id" => "10000",
			"name" => ["en-US" => "Pant sizes"],
			"enabled" => true,
			"categories" => ["30000", "50000"],
			"metric_units" => 1,
			"countries" => ["ES", "US"],
			"columns" => [
				["en-US" => "Inside leg"],
				["en-US" => "Rise"],
				["en-US" => "Waist"]
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
