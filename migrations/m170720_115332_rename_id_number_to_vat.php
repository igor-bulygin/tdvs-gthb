<?php

class m170720_115332_rename_id_number_to_vat extends \yii\mongodb\Migration
{
    public function up()
    {
		Yii::$app->mongodb->getCollection('order')->update(
			[],
			[
				'$rename' => [
					'shipping_address.id_number' => 'shipping_address.vat_id',
					'billing_address.id_number' => 'billing_address.vat_id',
				],
			]
		);

		Yii::$app->mongodb->getCollection('person')->update(
			[],
			[
				'$rename' => [
					'person_info.id_number' => 'person_info.vat_id',
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
					'shipping_address.vat_id' => 'shipping_address.id_number',
					'billing_address.vat_id' => 'billing_address.id_number',
				],
			]
		);

		Yii::$app->mongodb->getCollection('person')->update(
			[],
			[
				'$rename' => [
					'person_info.vat_id' => 'person_info.id_number',
				],
			]
		);
    }
}
