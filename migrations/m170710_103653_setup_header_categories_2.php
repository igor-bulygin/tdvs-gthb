<?php

class m170710_103653_setup_header_categories_2 extends \yii\mongodb\Migration
{
    public function up()
    {
    	// Configure the categories in the header "shop by deparment" menu

    	$categories = [
			'1a23b' => 1,
			'4a2b4' => 2,
			'ca82k' => 3,
			'f0cco' => 4,
			'3f78g' => 5,
			'2r67s' => 6,
			'cc29g' => 7,

			'1b34c' => 1,
			'1e67f' => 2,
			'1h10i' => 3,
			'1i11j' => 4,
			'1k13l' => 5,

			'4b3c5' => 1,
			'4x1a2' => 2,

			'22ecr' => 1,
			'6da27' => 2,
			'3ac6t' => 3,
			'4c1d2' => 4,
			'5d2ek' => 5,
			'bf73v' => 6,
			'd9aaa' => 7,
			'e7d15' => 8,
			'b6718' => 9,

			'ada11' => 1,
			'22911' => 2,
			'4x1b3' => 3,
			'ab0a7' => 4,
			'8d1a2' => 5,
			'9e5e6' => 6,
			'b5144' => 7,
			'2029g' => 8,

			'6a5ba' => 1,
			'ada8i' => 2,
			'e84fy' => 3,
			'a2880' => 4,

			'31e28' => 1,
			'959dw' => 2,
			'ea50g' => 3,
			'0c1cn' => 4,
			'c0103' => 5,
			'bea4j' => 6,
			'9b134' => 7,
			'e6dej' => 8,
			'81e13' => 9,

			'ef4ch' => 1,
			'3abc9' => 2,
			'3klm5' => 3,
			'3pq54' => 4,
			'3145q' => 5,
			'3lva9' => 6,

			'2a10b' => 1,
			'7707g' => 2,
			'77a6u' => 3,
			'2237e' => 4,
			'f9ed9' => 5,
			'5c361' => 6,

			'fa885' => 1,
			'611f0' => 2,
			'3dff5' => 3,
			'00dd6' => 4,
			'9a833' => 5,
			'9766d' => 6,
			'a5209' => 7,
			'b93c0' => 8,
			'62da1' => 9,
    		
		];

		\app\models\Category::setSerializeScenario(\app\models\Category::SERIALIZE_SCENARIO_OWNER);
    	foreach ($categories as $categoryId => $position) {
    		$category = \app\models\Category::findOneSerialized($categoryId);
    		if ($category) {
    			$category->header_position = $position;
				$category->save();
			}
		}

    }

    public function down()
    {
        return false;
    }
}
