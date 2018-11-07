<?php

class m181107_145217_create_payments_errors extends \yii\mongodb\Migration {
    public function up()
    {
      $this->createCollection('payment_errors');
      $this->createIndex('payment_errors', 'short_id', [
        'unique' => true
      ]);
    }

    public function down()
    {
        echo "m181107_145117_create_payments_errors cannot be reverted.\n";
        return false;
    }

    /*
    // Use safeUp/safeDown to run migration code within a transaction
    public function safeUp()
    {
    }

    public function safeDown()
    {
    }
    */
}
