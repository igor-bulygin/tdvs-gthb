<?php

use yii\mongodb\Migration;

class m150415_113659_create_category extends Migration {
	public function up() {
		$this->createCollection('category');
		$this->createIndex('category', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('category', 'path');

		$this->insert('category', [
			"short_id" => 10000,
			"path" => '/',
			"name" => ["en-US" => "Fashion"],
			"slug" => ["en-US" => "fashion"]
		]);

		$this->insert('category', [
			"short_id" => 20000,
			"path" => '/10000/',
			"name" => ["en-US" => "Women"],
			"slug" => ["en-US" => "women"]
		]);

		$this->insert('category', [
			"short_id" => 30000,
			"path" => '/10000/20000/',
			"name" => ["en-US" => "Dresses"],
			"slug" => ["en-US" => "Dresses"]
		]);

		$this->insert('category', [
			"short_id" => 40000,
			"path" => '/10000/',
			"name" => ["en-US" => "Man"],
			"slug" => ["en-US" => "man"]
		]);

		$this->insert('category', [
			"short_id" => 50000,
			"path" => '/10000/40000/',
			"name" => ["en-US" => "Jeans"],
			"slug" => ["en-US" => "jeans"]
		]);

		$this->insert('category', [
			"short_id" => 60000,
			"path" => '/',
			"name" => ["en-US" => "Technology"],
			"slug" => ["en-US" => "technology"]
		]);

		$this->insert('category', [
			"short_id" => 70000,
			"path" => '/60000/',
			"name" => ["en-US" => "Computers"],
			"slug" => ["en-US" => "computers"]
		]);

		$this->insert('category', [
			"short_id" => 80000,
			"path" => '/60000/70000/',
			"name" => ["en-US" => "RAM"],
			"slug" => ["en-US" => "ram"]
		]);

		$this->insert('category', [
			"short_id" => 90000,
			"path" => '/60000/',
			"name" => ["en-US" => "Smart phones"],
			"slug" => ["en-US" => "smart-phones"]
		]);
	}

	public function down() {
		$this->dropAllIndexes('category');
		$this->dropCollection('category');
	}
}
