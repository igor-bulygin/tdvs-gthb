<?php

use app\models\Lang;
use app\models\Person;
use app\helpers\Currency;
use yii\mongodb\Migration;

class m150405_184924_create_person extends Migration {
	public function up() {
		$en = array_keys(Lang::EN_US)[0];

		$this->createCollection('person');
		$this->createIndex('person', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('person', 'type');
		$this->createIndex('person', 'credentials.email');

		$this->insert('person', [
			"short_id" => "10000",
			"type" => [Person::DEVISER],
			"personal_info" => [
				"name" => "Deviser",
				"surnames" => ["Foo", "Bar"],
				"bday" => new MongoDate(strtotime("1990-01-06 00:00:00"))
			],
			"credentials" => [
				"email" => "deviser@test.com"
			],
			"preferences" => [
				"language" => $en,
				"currency" => Currency::_EUR
			]
		]);
	}

	public function down() {
		$this->dropAllIndexes('person');
		$this->dropCollection('person');
	}
}
