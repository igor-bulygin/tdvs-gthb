<?php

class m170418_213437_create_stories extends \yii\mongodb\Migration
{
    public function up()
    {
		$this->createCollection('story');
		$this->createIndex('story', 'short_id', [
			'unique' => true
		]);
		$this->createIndex('story', 'story_state & person_id');
    }

    public function down()
    {
		$this->dropAllIndexes('story');
		$this->dropCollection('story');
    }
}
