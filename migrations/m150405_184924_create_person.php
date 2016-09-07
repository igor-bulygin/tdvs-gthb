<?php

use app\models\Lang;
use app\models\Person;
use app\helpers\Currency;
use yii\mongodb\Migration;

class m150405_184924_create_person extends Migration {
	public function up() {
		$en = array_keys(Lang::EN_US_DESC)[0];

		$this->createCollection('person');
		$this->createIndex('person', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('person', 'type');
		$this->createIndex('person', 'categories');
		$this->createIndex('person', 'credentials.email');

		$this->insert('person', [
			"short_id" => "1000000",
			"type" => [Person::ADMIN],
			"personal_info" => [
				"name" => "Admin",
				"surnames" => ["Foo", "Bar"],
				"bday" => new MongoDate(strtotime("1990-01-06 03:42:15")),
				"country" => "US"
			],
			"credentials" => [
				"email" => "admin@test.com"
			],
			"preferences" => [
				"language" => $en,
				"currency" => Currency::_EUR
			]
		]);

		/* @var $person \app\models\Person */
		$person = Person::findOne(["short_id" => "1000000"]);
		$person->setPassword("admin_test_123");
		$person->update(false);

		$this->insert('person', [
			"short_id" => "2000000",
			"type" => [Person::CLIENT],
			"personal_info" => [
				"name" => "Client",
				"surnames" => ["Foo", "Bar"],
				"bday" => new MongoDate(strtotime("1990-01-06 03:42:15")),
				"country" => "US"
			],
			"credentials" => [
				"email" => "client@test.com"
			],
			"preferences" => [
				"language" => $en,
				"currency" => Currency::_EUR
			]
		]);

		/* @var $person \app\models\Person */
		$person = Person::findOne(["short_id" => "2000000"]);
		$person->setPassword("client_test_123");
		$person->update(false);

		$this->insert('person', [
			"short_id" => "3000000",
			"type" => [Person::DEVISER],
			"personal_info" => [
				"name" => "Deviser",
				"surnames" => ["Foo", "Bar"],
				"bday" => new MongoDate(strtotime("1990-01-06 03:42:15")),
				"country" => "US"
			],
			"slug" => "deviser-foo-bar",
			"credentials" => [
				"email" => "deviser@test.com"
			],
			"preferences" => [
				"language" => $en,
				"currency" => Currency::_EUR
			]
		]);

		/* @var $person \app\models\Person */
		$person = Person::findOne(["short_id" => "3000000"]);
		$person->setPassword("deviser_test_123");
		$person->update(false);

		$this->insert('person', [
			"short_id" => "4000000",
			"type" => [Person::COLLABORATOR],
			"categories" => ["30000", "50000"],
			"personal_info" => [
				"name" => "Collaborator",
				"surnames" => ["Foo", "Bar"],
				"bday" => new MongoDate(strtotime("1990-01-06 03:42:15")),
				"country" => "US"
			],
			"credentials" => [
				"email" => "collaborator@test.com"
			],
			"preferences" => [
				"language" => $en,
				"currency" => Currency::_EUR
			]
		]);

		/* @var $person \app\models\Person */
		$person = Person::findOne(["short_id" => "4000000"]);
		$person->setPassword("collaborator_test_123");
		$person->update(false);
	}

	public function down() {
		$this->dropAllIndexes('person');
		$this->dropCollection('person');
	}
}
