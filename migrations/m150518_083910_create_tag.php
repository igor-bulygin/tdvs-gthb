<?php

class m150518_083910_create_tag extends \yii\mongodb\Migration {
	public function up() {
		$this->createCollection('tag');
		$this->createIndex('tag', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('tag', 'categories');
		$this->createIndex('tag', 'enabled');

		$this->insert('tag', [
			"short_id" => 10000,
			"enabled" => true,
			"required" => true,
			"type" => 0,
			"n_options" => 2,
			"name" => ["en-US" => "Color"],
			"description" => ["en-US" => "Color(s) of the product"],
			"categories" => [30000, 50000],
			"options" => [
				[
					"text" => ["en-US" => "Red"],
					"value" => "red",
				],
				[
					"text" => ["en-US" => "Green"],
					"value" => "green",
				],
				[
					"text" => ["en-US" => "Blue"],
					"value" => "blue",
				]
			]
		]);

		$this->insert('tag', [
			"short_id" => 20000,
			"enabled" => true,
			"required" => false,
			"type" => 1,
			"name" => ["en-US" => "Weight"],
			"description" => ["en-US" => "Weight of the product"],
			"categories" => [90000],
			"options" => [
				[
					"text" => ["en-US" => "Weight"],
					"type" => 0,
					"metric_units" => 2
				]
			]
		]);

		$this->insert('tag', [
			"short_id" => 30000,
			"enabled" => true,
			"required" => false,
			"type" => 1,
			"name" => ["en-US" => "Size"],
			"description" => ["en-US" => "Size of the product"],
			"categories" => [90000],
			"options" => [
				[
					"text" => ["en-US" => "Long"],
					"type" => 0,
					"metric_units" => 1
				],
				[
					"text" => ["en-US" => "Wide"],
					"type" => 0,
					"metric_units" => 1
				],
				[
					"text" => ["en-US" => "Tall"],
					"type" => 0,
					"metric_units" => 1
				],
			]
		]);
	}

	public function down() {
		$this->dropAllIndexes('tag');
		$this->dropCollection('tag');
	}
}
