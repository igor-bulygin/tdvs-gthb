<?php

class m170720_120755_rename_id_number_to_vat_v2 extends \yii\mongodb\Migration
{
    public function up()
    {
		Yii::$app->mongodb->getCollection('person')->update(
			[],
			[
				'$rename' => [
					'personal_info.id_number' => 'personal_info.vat_id',
				],
			]
		);
    }

    public function down()
    {
		Yii::$app->mongodb->getCollection('person')->update(
			[],
			[
				'$rename' => [
					'personal_info.vat_id' => 'personal_info.id_number',
				],
			]
		);
    }
}
