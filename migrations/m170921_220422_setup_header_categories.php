<?php

use app\models\Category;

class m170921_220422_setup_header_categories extends \yii\mongodb\Migration
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
				'a5d17e5',
				'456248y',
			],

			'f9fa9' => [ // beauty
				'd2ddf97',
				'c3aa7de',
				'b2361ai',
			],

			'2r67s' => [ // deco
				'8972393',
				'ad07d9h',
				'e7361dz',
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
        echo "m170921_220422_setup_header_categories cannot be reverted.\n";

        return false;
    }
}
