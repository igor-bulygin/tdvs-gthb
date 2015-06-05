<?php

use yii\mongodb\Migration;

class m150405_184924_create_person extends Migration {
	public function up() {
		$this->createCollection('person');
		$this->createIndex('person', 'short_id', [
			'unique' => true
		]);

		$this->insert('person', [
			"short_id" => 10000,
			"path" => '/',
			"name" => ["en-US" => "Category 1 (10000)"],
			"slug" => ["en-US" => "category-1"]
		]);
	}

	public function down() {
		$this->dropAllIndexes('person');
		$this->dropCollection('person');
	}
}
