<?php

class m150518_083942_create_product extends \yii\mongodb\Migration {
	public function up() {

		$this->insert('tag', [
			"short_id" => "10000000",
			"enabled" => true,

			"categories" => ["30000", "50000"],

			"options" => [
				"10000" => [
					"type" => 0,
					"values" => ["red", "green"]
				]
			]
		]);

	}

	public function down()
	{

	}
}
