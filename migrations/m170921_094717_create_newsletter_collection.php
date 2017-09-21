<?php

class m170921_094717_create_newsletter_collection extends \yii\mongodb\Migration
{
    public function up()
    {
		$this->createCollection('newsletter');
		$this->createIndex('newsletter', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('newsletter', 'email', [
			'unique' => true
		]);
    }

    public function down()
	{
		$this->dropAllIndexes('newsletter');
		$this->dropCollection('newsletter');
	}
}
