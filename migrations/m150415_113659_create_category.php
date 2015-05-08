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
			"name" => ["en-US" => "Category 1 (10000)"],
			"slug" => ["en-US" => "category-1"]
		]);

		$this->insert('category', [
			"short_id" => 20000,
			"path" => '/10000/',
			"name" => ["en-US" => "Category 2 (20000)"],
			"slug" => ["en-US" => "category-2"]
		]);

		$this->insert('category', [
			"short_id" => 30000,
			"path" => '/10000/20000/',
			"name" => ["en-US" => "Category 3 (30000)"],
			"slug" => ["en-US" => "category-3"]
		]);

		$this->insert('category', [
			"short_id" => 40000,
			"path" => '/10000/20000/',
			"name" => ["en-US" => "Category 4 (40000)"],
			"slug" => ["en-US" => "category-4"]
		]);

		$this->insert('category', [
			"short_id" => 50000,
			"path" => '/10000/',
			"name" => ["en-US" => "Category 5 (50000)"],
			"slug" => ["en-US" => "category-5"]
		]);

		$this->insert('category', [
			"short_id" => 60000,
			"path" => '/10000/50000/',
			"name" => ["en-US" => "Category 6 (60000)"],
			"slug" => ["en-US" => "category-6"]
		]);

		$this->insert('category', [
			"short_id" => 70000,
			"path" => '/',
			"name" => ["en-US" => "Category 7 (70000)"],
			"slug" => ["en-US" => "category-7"]
		]);

		$this->insert('category', [
			"short_id" => 80000,
			"path" => '/70000/',
			"name" => ["en-US" => "Category 8 (80000)"],
			"slug" => ["en-US" => "category-8"]
		]);

		$this->insert('category', [
			"short_id" => 90000,
			"path" => '/70000/',
			"name" => ["en-US" => "Category 9 (90000)"],
			"slug" => ["en-US" => "category-9"]
		]);
	}

	public function down() {
		$this->dropAllIndexes('category');
		$this->dropCollection('category');
	}
}
