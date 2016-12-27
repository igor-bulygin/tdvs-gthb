<?php

use yii\mongodb\Migration;

class m161227_103006_create_order extends Migration
{
    public function up()
    {
        $this->createCollection('order');
        $this->createIndex('order', 'short_id', [
            'unique' => true
        ]);
    }

    public function down()
    {
        $this->dropAllIndexes('order');
        $this->dropCollection('order');
    }
}
