<?php

class m170426_112545_update_indexes extends \yii\mongodb\Migration
{
	public function up()
	{

		try {
			$this->dropCollection('tern');
			$this->dropIndex('country', 'country_code');
		} catch (\yii\mongodb\Exception $e) {
			// nothing to do...
		}

		$this->createIndex('country', 'country_code', [
			'unique' => true
		]);

		$this->createIndex('box', 'short_id', [
			'unique' => true
		]);

		$this->createIndex('loved', 'short_id', [
			'unique' => true
		]);

		$this->createIndex('invitation', 'uuid', [
			'unique' => true
		]);

		$this->createIndex('postman_email', 'uuid', [
			'unique' => true
		]);
	}

	public function down()
	{
		try {
			$this->dropIndex('country', 'country_code');
			$this->createIndex('country', 'country_code');
		} catch (\yii\mongodb\Exception $e) {
			// nothing to do...
		}

		$this->dropIndex('box', 'short_id');

		$this->dropIndex('loved', 'short_id');

		$this->dropIndex('invitation', 'uuid');

		$this->dropIndex('postman_email', 'uuid');
	}
}
