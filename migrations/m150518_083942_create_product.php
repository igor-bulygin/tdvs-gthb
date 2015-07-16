<?php

use app\models\Lang;
use app\models\Returns;
use app\models\Bespoke;
use app\models\Preorder;
use app\models\SizeChart;
use app\models\Warranty;
use app\models\MetricUnit;
use app\models\MadeToOrder;
use app\helpers\Currency as Currency_helper;

class m150518_083942_create_product extends \yii\mongodb\Migration {
	public function up() {
		$en = array_keys(Lang::EN_US)[0];

		$this->createCollection('product');
		$this->createIndex('product', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('product', 'deviser_id');
		$this->createIndex('product', 'categories');
		$this->createIndex('product', 'enabled');

		$this->insert('product', [
			"short_id" => "10000000",
			"deviser_id" => "3000000",
			"enabled" => true,

			"categories" => ["4d2e3"],

			"collections" => ["10000", "20000"],

			"name" => [
				$en => "Name of product"
			],

			"description" => [
				$en => "Description"
			],

			"media" => [
				"videos_links" => [
					"link 1",
					"link 2"
				],

				"photos" => [

				]
			],

			"options" => [
				"10000" => [
					["blue"],
					["red", "green"],
					["white", "black"]
				],
				"60000" => [
					[
						[
							"metric_unit" => "m",
							"value" => 10
						],
						[
							"metric_unit" => "m",
							"value" => 200
						]
					],
					[
						[
							"metric_unit" => "cm",
							"value" => 35
						],
						[
							"metric_unit" => "cm",
							"value" => 45
						]
					]
				]
			],

			"madetoorder" => [
				"type" => MadeToOrder::DAYS,
				"value" => 20
			],

			"sizechart" => [
				"short_id" => "12000",
				"type" => SizeChart::DEVISER,
				"deviser_id" => "3000000",
				"metric_unit" => MetricUnit::MM,
				"name" => "My custom sizechart",
				"country" => "US",
				"columns" => [
					[$en => "Length"],
					[$en => "Waist"],
					[$en => "Bust"],
					[$en => "Shoulder Width"],
					[$en => "Sleeve Length"],
					[$en => "Sleeve Opening"],
					[$en => "Collar"]
				],
				"values" => [
					["2", "600", "450", "500", "400", "200", "50", "75"],
					["4", "625", "475", "525", "425", "225", "75", "100"],
					["6", "650", "500", "550", "450", "250", "100", "125"],
					["8", "675", "525", "575", "475", "275", "125", "150"],
					["10", "700", "550", "600", "500", "300", "125", "175"],
					["12", "725", "575", "625", "525", "325", "125", "175"],
					["14", "750", "600", "650", "550", "350", "125", "175"],
					["16", "775", "625", "675", "550", "350", "150", "175"]
				]
			],

			"bespoke" => [
				"type" => Bespoke::YES,
				"value" => [0, 2, 4]
			],

			"preorder" => [
				"type" => Preorder::YES,
				"preorder_end" => new MongoDate(strtotime("2010-02-18 00:00:00")),
				"shipping" => new MongoDate(strtotime("2013-03-24 00:00:00"))
			],

			"returns" => [
				"type" => Returns::DAYS,
				"value" => 14
			],

			"warranty" => [
				"type" => Warranty::DAYS,
				"value" => 365
			],

			"currency" => Currency_helper::_EUR,
			"weight_unit" => MetricUnit::G,

			"price_stock" => [
				[
					"size" => "6",
					"options" => [
						"10000" => ["blue"],
						"60000" => [
							[
								"metric_unit" => "m",
								"value" => 10
							],
							[
								"metric_unit" => "m",
								"value" => 200
							]
						]
					],
					"weight" => 200,
					"price" => 400,
					"price_eur" => 400,
					"stock" => 4
				],
				[
					"size" => "6",
					"options" => [
						"10000" => ["red", "green"],
						"60000" => [
							[
								"metric_unit" => "cm",
								"value" => 35
							],
							[
								"metric_unit" => "cm",
								"value" => 45
							]
						]
					],
					"weight" => 200,
					"price" => 400,
					"price_eur" => 400,
					"stock" => 4
				]
			]
		]);

	}

	public function down() {
		$this->dropAllIndexes('product');
		$this->dropCollection('product');
	}
}
