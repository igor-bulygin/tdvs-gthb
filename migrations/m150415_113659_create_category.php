<?php

use app\models\Lang;
use yii\mongodb\Migration;

class m150415_113659_create_category extends Migration {
	public function up() {
		$en = array_keys(Lang::EN_US)[0];

		$this->createCollection('category');
		$this->createIndex('category', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('category', 'path');

		$this->insert('category', [
			"short_id" => "10000",
			"path" => '/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Fashion"],
			"slug" => [$en => "fashion"]
		]);

		$this->insert('category', [
			"short_id" => "20000",
			"path" => '/10000/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Women"],
			"slug" => [$en => "women"]
		]);

		$this->insert('category', [
			"short_id" => "30000",
			"path" => '/10000/20000/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Dresses"],
			"slug" => [$en => "Dresses"]
		]);

		$this->insert('category', [
			"short_id" => "40000",
			"path" => '/10000/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Man"],
			"slug" => [$en => "man"]
		]);

		$this->insert('category', [
			"short_id" => "50000",
			"path" => '/10000/40000/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Jeans"],
			"slug" => [$en => "jeans"]
		]);

		$this->insert('category', [
			"short_id" => "60000",
			"path" => '/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Technology"],
			"slug" => [$en => "technology"]
		]);

		$this->insert('category', [
			"short_id" => "70000",
			"path" => '/60000/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Computers"],
			"slug" => [$en => "computers"]
		]);

		$this->insert('category', [
			"short_id" => "80000",
			"path" => '/60000/70000/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "RAM"],
			"slug" => [$en => "ram"]
		]);

		$this->insert('category', [
			"short_id" => "90000",
			"path" => '/60000/',
			"sizecharts" => false,
			"prints" => false,
			"name" => [$en => "Smart phones"],
			"slug" => [$en => "smart-phones"]
		]);
	}

	public function down() {
		$this->dropAllIndexes('category');
		$this->dropCollection('category');
	}
}
