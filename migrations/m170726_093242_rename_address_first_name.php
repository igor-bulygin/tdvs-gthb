<?php

class m170726_093242_rename_address_first_name extends \yii\mongodb\Migration
{
	public function up()
	{
		Yii::$app->mongodb->getCollection('order')->update(
			[
				'shipping_address.first_name' => [
					'$exists' => true,
				],
				'billing_address.first_name' => [
					'$exists' => true,
				],
			],
			[
				'$rename' => [
					'shipping_address.first_name' => 'shipping_address.name',
					'billing_address.first_name' => 'billing_address.name',
				],
			]
		);
	}

	public function down()
	{
		Yii::$app->mongodb->getCollection('order')->update(
			[
				'shipping_address.name' => [
					'$exists' => true,
				],
				'billing_address.name' => [
					'$exists' => true,
				],
			],
			[
				'$rename' => [
					'shipping_address.name' => 'shipping_address.first_name',
					'billing_address.name' => 'billing_address.first_name',
				],
			]
		);
	}
}
