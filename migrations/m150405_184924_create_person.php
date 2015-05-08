<?php

use yii\mongodb\Migration;

class m150405_184924_create_person extends Migration {
	public function up() {
		$this->createCollection('person');
		$this->createIndex('person', 'short_id', [
			'unique' => true
		]);
	}

	public function down() {
		$this->dropAllIndexes('person');
		$this->dropCollection('person');
	}
}
