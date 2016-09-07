<?php

use app\models\Lang;
use app\models\Faq;
use yii\mongodb\Migration;

class m160907_105824_invitation extends Migration
{
    public function up()
    {
	    $this->createCollection('invitation');
	    $this->createIndex('invitation', 'uuid', [
		    'unique' => true
	    ]);
    }

    public function down()
    {
	    $this->dropAllIndexes('invitation');
	    $this->dropCollection('invitation');

        return false;
    }
}
