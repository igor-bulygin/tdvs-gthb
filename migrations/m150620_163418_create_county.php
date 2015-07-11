<?php

use app\models\Lang;
use app\helpers\Utils;
use app\models\Country;

class m150620_163418_create_county extends \yii\mongodb\Migration {
	public function up() {
		$en = array_keys(Lang::EN_US)[0];

		$this->createCollection('country');
		$this->createIndex('country', 'country_code', [
			'unique' => true
		]);
		$this->createIndex('country', 'continent');
		$this->createIndex('country', 'currency_code');

		$s_country = file_get_contents(dirname(__FILE__) . DIRECTORY_SEPARATOR . "country.json");
		$country_json = json_decode($s_country, true);

		foreach($country_json as $country) {
			$data = Utils::underscoreKeys($country);
			$name = $data["country_name"];
			$data["country_name"] = [];
			$data["country_name"][$en] = $name;

			/* @var $country \app\models\Country */
			$country = new Country();
			$country->setAttributes($data, false);
			$country->save(false);
		}

		// Let's insert this special country called... Europe!
		$country = new Country();
		$country->setAttributes([
			"country_code" => "EU",
			"country_name" => [
				$en => "Europe"
			],
			"currency_code" => "EUR",
			"continent" => "EU"
		], false);
		$country->save(false);
	}

	public function down() {
		$this->dropAllIndexes('country');
		$this->dropCollection('country');
	}
}
