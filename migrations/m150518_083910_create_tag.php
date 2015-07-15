<?php

use app\models\Tag;
use app\models\Lang;
use app\models\TagOption;
use app\models\MetricType;

class m150518_083910_create_tag extends \yii\mongodb\Migration {
	public function up() {
		$en = array_keys(Lang::EN_US)[0];

		$this->createCollection('tag');
		$this->createIndex('tag', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('tag', 'categories');
		$this->createIndex('tag', 'enabled');

		$this->insert('tag', [
			"short_id" => "10000",
			"enabled" => true,
			"required" => true,
			"type" => Tag::DROPDOWN,
			"n_options" => 2,
			"name" => [$en => "Color"],
			"description" => [$en => "Color for fashion"],
			"categories" => ["4d2e3", "4e6f9", "4x9a5", "4x8b4"],
			"options" => [
				[
					"text" => [$en => "Red"],
					"value" => "red",
				],
				[
					"text" => [$en => "Green"],
					"value" => "green",
				],
				[
					"text" => [$en => "Blue"],
					"value" => "blue",
				],
				[
					"text" => [$en => "Black"],
					"value" => "black",
				],
				[
					"text" => [$en => "Yellow"],
					"value" => "yellow",
				],
				[
					"text" => [$en => "Orange"],
					"value" => "orange",
				],
				[
					"text" => [$en => "White"],
					"value" => "white",
				],
				[
					"text" => [$en => "Purple"],
					"value" => "purple",
				]
			]
		]);

		$this->insert('tag', [
			"short_id" => "20000",
			"enabled" => true,
			"required" => false,
			"type" => Tag::FREETEXT,
			"name" => [$en => "Size"],
			"description" => [$en => "Size of the product"],
			"categories" => ["1h10i", "1i11j"],
			"options" => [
				[
					"text" => [$en => "Long"],
					"type" => TagOption::NUMERIC,
					"metric_type" => MetricType::SIZE
				],
				[
					"text" => [$en => "Wide"],
					"type" => TagOption::NUMERIC,
					"metric_type" => MetricType::SIZE
				],
				[
					"text" => [$en => "Tall"],
					"type" => TagOption::NUMERIC,
					"metric_type" => MetricType::SIZE
				],
			]
		]);

		$this->insert('tag', [
			"short_id" => "30000",
			"enabled" => true,
			"required" => true,
			"type" => Tag::DROPDOWN,
			"n_options" => 3,
			"name" => [$en => "Subject Matter"],
			"description" => [$en => "Subject Matter for Art"],
			"categories" => ["1b34c", "1c45d", "1d56e", "1e67f", "1f78g", "1g89h", "1h10i", "1i11j", "1j12k", "1k13l"],
			"options" => [
				[
					"text" => [$en => "Abstract"],
					"value" => "abstract",
				],
				[
					"text" => [$en => "Animals"],
					"value" => "animals",
				],
				[
					"text" => [$en => "Americana"],
					"value" => "americana",
				],
				[
					"text" => [$en => "Arquitecture"],
					"value" => "arquitecture",
				],
				[
					"text" => [$en => "Cartoon"],
					"value" => "cartoon",
				],
				[
					"text" => [$en => "Children"],
					"value" => "children",
				],
				[
					"text" => [$en => "Cuisine"],
					"value" => "cuisine",
				],
				[
					"text" => [$en => "Education"],
					"value" => "education",
				],
				[
					"text" => [$en => "Erotic"],
					"value" => "erotic",
				],
				[
					"text" => [$en => "Fantasy"],
					"value" => "fantasy",
				],
				[
					"text" => [$en => "Fashion"],
					"value" => "fashion",
				],
				[
					"text" => [$en => "Health & Beauty"],
					"value" => "healthbeauty",
				],
				[
					"text" => [$en => "Humor"],
					"value" => "humor",
				],
				[
					"text" => [$en => "Landscape"],
					"value" => "landscape",
				],
				[
					"text" => [$en => "Love"],
					"value" => "love",
				],
				[
					"text" => [$en => "Nature"],
					"value" => "nature",
				],
				[
					"text" => [$en => "People"],
					"value" => "people",
				],
				[
					"text" => [$en => "Performing Arts"],
					"value" => "performingarts",
				],
				[
					"text" => [$en => "Places"],
					"value" => "places",
				],
				[
					"text" => [$en => "Political"],
					"value" => "political",
				],
				[
					"text" => [$en => "Religious"],
					"value" => "religious",
				],
				[
					"text" => [$en => "Science"],
					"value" => "science",
				],
				[
					"text" => [$en => "Sports"],
					"value" => "sports",
				],
				[
					"text" => [$en => "Still Life"],
					"value" => "stilllife",
				],
				[
					"text" => [$en => "Urban"],
					"value" => "urban",
				],
				[
					"text" => [$en => "Other"],
					"value" => "othersubject",
				]
			]
		]);

		$this->insert('tag', [
			"short_id" => "40000",
			"enabled" => true,
			"required" => true,
			"type" => Tag::DROPDOWN,
			"n_options" => 2,
			"name" => [$en => "Style"],
			"description" => [$en => "Style for Jewelry"],
			"categories" => ["3h55f", "3n05a", "3a12b", "3op1b", "3l15v", "3n05x", "3abc9", "3klm5", "3pq54", "3145q", "3lva9", "39sea"],
			"options" => [
				[
					"text" => [$en => "Bright Colors"],
					"value" => "brightcolors",
				],
				[
					"text" => [$en => "Classic Chic"],
					"value" => "classicchic",
				],
				[
					"text" => [$en => "Cocktail Parties"],
					"value" => "cocktailparties",
				],
				[
					"text" => [$en => "Formal"],
					"value" => "formal",
				],
				[
					"text" => [$en => "Sparkling"],
					"value" => "sparkling",
				],
				[
					"text" => [$en => "Tribal Trend"],
					"value" => "tribaltrend",
				]
			]
		]);

		$this->insert('tag', [
			"short_id" => "50000",
			"enabled" => true,
			"required" => true,
			"type" => Tag::DROPDOWN,
			"n_options" => 1,
			"name" => [$en => "Season"],
			"description" => [$en => "Season for fashion"],
			"categories" => ["4d2e3", "4e6f9", "4x9a5", "4x8b4"],
			"options" => [
				[
					"text" => [$en => "Spring"],
					"value" => "spring",
				],
				[
					"text" => [$en => "Summer"],
					"value" => "summer",
				],
				[
					"text" => [$en => "Fall"],
					"value" => "fall",
				],
				[
					"text" => [$en => "Winter"],
					"value" => "winter",
				]
			]
		]);

		$this->insert('tag', [
			"short_id" => "60000",
			"enabled" => true,
			"required" => true,
			"type" => Tag::FREETEXT,
			"name" => [$en => "Test"],
			"description" => [$en => "Test"],
			"categories" => ["4d2e3"],
			"options" => [
				[
					"text" => [$en => "Field A"],
					"type" => TagOption::NUMERIC,
					"metric_type" => MetricType::SIZE
				],
				[
					"text" => [$en => "Field B"],
					"type" => TagOption::NUMERIC,
					"metric_type" => MetricType::SIZE
				]
			]
		]);
	}

	public function down() {
		$this->dropAllIndexes('tag');
		$this->dropCollection('tag');
	}
}
