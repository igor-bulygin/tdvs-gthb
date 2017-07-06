<?php
use app\models\Category;

class m170703_101025_setup_header_categories extends \yii\mongodb\Migration
{
    public function up()
	{
		$setup = [

			'1a23b' => [ // art
				'b35cb8e',
				'3bca1b15',
				'b479b9ad',
			],

			'4a2b4' => [ // fashion
				'a39ae909',
				'5e4c190c',
				'5222de87',
			],

			'f9fa9' => [ // beauty

			],

			'f0cco' => [ // technology

			],

			'3f78g' => [ //jewelry
				'e0338af7',
				'42ab9269',
				'b5188907',
			],

			'2r67s' => [  // interior design
				'1d71d7fb',
				'dd095081',
				'af0b8163',
			],

			'ca82k' => [ // sports

			],

		];

		$i = 1;
		Category::setSerializeScenario(\app\models\Category::SERIALIZE_SCENARIO_ADMIN);
		foreach ($setup as $categoryId => $products) {
			$category = Category::findOneSerialized($categoryId);
			if ($category) {
				$category->header_position = $i;
				$category->header_products = $products;
				$category->save();
			}
			$i++;
		}
	}

    public function down()
    {
        echo "m170703_101025_setup_header_categories cannot be reverted.\n";

        return false;
    }
}
