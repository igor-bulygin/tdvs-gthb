<?php

class m170831_171517_update_categories_slug extends \yii\mongodb\Migration
{
    public function up()
    {
    	\app\models\Category::setSerializeScenario(\app\models\Category::SERIALIZE_SCENARIO_ADMIN);
    	$categories = \app\models\Category::findSerialized();
		foreach ($categories as $category) {
			$category->save(false);
		}

		return true;
    }

    public function down()
    {
        echo "m170831_171517_update_categories_slug cannot be reverted.\n";

        return false;
    }
}
