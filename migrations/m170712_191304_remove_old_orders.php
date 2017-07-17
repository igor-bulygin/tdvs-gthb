<?php

class m170712_191304_remove_old_orders extends \yii\mongodb\Migration
{
    public function up()
    {
    	Yii::$app->mongodb->getCollection('order')->remove([]);

		return false;

    }

    public function down()
    {
		echo "m170712_191304_update_order_model cannot be reverted.\n";

        return false;
    }
}
