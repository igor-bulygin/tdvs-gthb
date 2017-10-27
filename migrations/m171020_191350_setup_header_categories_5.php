<?php

use app\models\Category;

class m171020_191350_setup_header_categories_5 extends \yii\mongodb\Migration
{
    public function up()
    {
		$setup = [

			'1a23b' => [ // art
				'3924973',
				'ccc8ecs',
				'e92d8f2',
			],

			'3f78g' => [ //jewelry
				'a45ad5e',
				'456248y',
				'a5d17e5',
			],

			'cc29g' => [ // beauty
				'd2ddf97',
				'27b11fv',
				'0e1259b',
			],

			'2r67s' => [ // deco
				'8972393',
				'e7361dz',
				'82a1fb6',
			],

			'4a2b4' => [ // fashion
				'e10892d',
				'48b87bn',
				'3285dak',
			],

			'ca82k' => [ // sports
				'605142h',
				'ec3621x',
				'a28788a',
			],

		];

		Category::setSerializeScenario(\app\models\Category::SERIALIZE_SCENARIO_ADMIN);
		foreach ($setup as $categoryId => $products) {
			$category = Category::findOneSerialized($categoryId);
			if ($category) {
				$category->header_products = $products;
				$category->save();
			}
		}
    }

    public function down()
    {
        echo "m171020_191350_setup_header_categories_5 cannot be reverted.\n";

        return false;
    }
}
