<?php

class m180626_094739_add_affiliates_to_person extends \yii\mongodb\Migration
{
    public function up()
    {
        $this->createIndex('person', 'affiliate_id');
        $this->createIndex('person', 'parent_affiliate_id');
    }

    public function down()
    {
        $this->dropIndex('person', 'affiliate_id');
        $this->dropIndex('person', 'parent_affiliate_id');
    }
}
