<?php

use app\helpers\Utils;
use app\models\Country;

class m150620_163418_create_county extends \yii\mongodb\Migration {
	public function up() {
		$this->createCollection('country');
		$this->createIndex('size_chart', 'country_code', [
			'unique' => true
		]);
		$this->createIndex('country', 'continent');
		$this->createIndex('country', 'currency_code');
		$this->createIndex('country', 'country_code');

		$s_country = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . "country.json");
		$country_json = json_decode($s_country, true);

		foreach($country_json as $country) {
			$data = Utils::underscoreKeys($country);
			$name = $data["country_name"];
			$data["country_name"] = [];
			$data["country_name"]["en-US"] = $name;

			/* @var $country \app\models\Country */
			$country = new Country();
			$country->setAttributes($data, false);
			$country->save(false);
		}
	}

	public function down() {
		$this->dropAllIndexes('country');
		$this->dropCollection('country');
	}
}
