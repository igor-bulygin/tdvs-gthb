<?php

class m170406_095406_create_indexes extends \yii\mongodb\Migration
{
    public function up()
	{
		// Eliminar todos los índices residuales
		$this->dropAllIndexes('person');
		$this->dropAllIndexes('product');
		$this->dropAllIndexes('category');
		$this->dropAllIndexes('tag');
		$this->dropAllIndexes('size_chart');
		$this->dropAllIndexes('country');
		$this->dropAllIndexes('faq');
		$this->dropAllIndexes('term');
		$this->dropAllIndexes('order');

		$this->createIndex('person', 'short_id', [
			'unique' => true,
		]);
		$this->createIndex('person', 'credentials.email');
		$this->createIndex('person', 'credentials.auth_key');
		$this->createIndex('person', 'type & categories');
		$this->createIndex('person', 'type & personal_info.country');
		$this->createIndex('person', 'type & account_state');
		$this->createIndex('person', 'videos.products');

		$this->createIndex('product', 'short_id', [
			'unique' => true,
		]);
		$this->createIndex('product', 'deviser_id');
		$this->createIndex('product', 'product_state');
		$this->createIndex('product', 'product_state & categories');
		$this->createIndex('product', 'product_state & deviser_id');

		$this->createIndex('category', 'short_id', [
			'unique' => true,
		]);
		$this->createIndex('category', 'path');

		$this->createIndex('tag', 'short_id', [
			'unique' => true,
		]);

		$this->createIndex('size_chart', 'short_id', [
			'unique' => true,
		]);
		$this->createIndex('size_chart', 'type');
		$this->createIndex('size_chart', 'type & deviser_id');

		$this->createIndex('country', 'country_code');

		$this->createIndex('faq', 'short_id', [
			'unique' => true,
		]);

		$this->createIndex('term', 'short_id', [
			'unique' => true,
		]);

		$this->createIndex('order', 'short_id', [
			'unique' => true,
		]);
	}

    public function down()
    {
		$this->dropAllIndexes('person');
		$this->dropAllIndexes('product');
		$this->dropAllIndexes('category');
		$this->dropAllIndexes('tag');
		$this->dropAllIndexes('size_chart');
		$this->dropAllIndexes('country');
		$this->dropAllIndexes('faq');
		$this->dropAllIndexes('term');
		$this->dropAllIndexes('order');

        return false;
    }
}
