<?php

class m170721_095157_rename_shipping_info_orders extends \yii\mongodb\Migration
{
    public function up()
    {
		Yii::$app->mongodb->getCollection('order')->update(
			[],
			[
				'$rename' => [
					'shipping_info.type' => 'shipping_type',
					'shipping_info.price' => 'shipping_price',
				],
			]
		);
    }

    public function down()
    {
		Yii::$app->mongodb->getCollection('order')->update(
			[],
			[
				'$rename' => [
					'shipping_type' => 'shipping_info.type',
					'shipping_price' => 'shipping_info.price',
				],
			]
		);
        return false;
    }
}
