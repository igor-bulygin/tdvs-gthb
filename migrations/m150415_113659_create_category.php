<?php

use yii\mongodb\Migration;

class m150415_113659_create_category extends Migration {
	public function up() {
		$this->createCollection('category');
		$this->createIndex('category', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('category', 'path');
	}

	public function down() {
		$this->dropAllIndexes('category');
		$this->dropCollection('category');
	}
}
