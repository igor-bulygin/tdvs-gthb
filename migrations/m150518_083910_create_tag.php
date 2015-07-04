<?php

use app\models\Lang;
use app\models\MetricType;

class m150518_083910_create_tag extends \yii\mongodb\Migration {
	public function up() {
		$en = array_keys(Lang::EN_US)[0];

		$this->createCollection('tag');
		$this->createIndex('tag', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('tag', 'categories');
		$this->createIndex('tag', 'enabled');

		$this->insert('tag', [
			"short_id" => "10000",
			"enabled" => true,
			"required" => true,
			"type" => 0,
			"n_options" => 2,
			"name" => [$en => "Color"],
			"description" => [$en => "Color(s) of the product"],
			"categories" => ["30000", "50000"],
			"options" => [
				[
					"text" => [$en => "Red"],
					"value" => "red",
				],
				[
					"text" => [$en => "Green"],
					"value" => "green",
				],
				[
					"text" => [$en => "Blue"],
					"value" => "blue",
				]
			]
		]);

		$this->insert('tag', [
			"short_id" => "20000",
			"enabled" => true,
			"required" => false,
			"type" => 1,
			"name" => [$en => "Weight"],
			"description" => [$en => "Weight of the product"],
			"categories" => ["90000"],
			"options" => [
				[
					"text" => [$en => "Weight"],
					"type" => 0,
					"metric_type" => MetricType::WEIGHT
				]
			]
		]);

		$this->insert('tag', [
			"short_id" => "30000",
			"enabled" => true,
			"required" => false,
			"type" => 1,
			"name" => [$en => "Size"],
			"description" => [$en => "Size of the product"],
			"categories" => ["90000"],
			"options" => [
				[
					"text" => [$en => "Long"],
					"type" => 0,
					"metric_type" => MetricType::SIZE
				],
				[
					"text" => [$en => "Wide"],
					"type" => 0,
					"metric_type" => MetricType::SIZE
				],
				[
					"text" => [$en => "Tall"],
					"type" => 0,
					"metric_type" => MetricType::SIZE
				],
			]
		]);
	}

	public function down() {
		$this->dropAllIndexes('tag');
		$this->dropCollection('tag');
	}
}
