<?php

use app\models\Lang;
use app\models\Faq;
use yii\mongodb\Migration;

class m160601_205106_create_faq extends Migration
{
  public function up() {
    $en = array_keys(Lang::EN_US)[0];

    $this->createCollection('faq');
		$this->createIndex('faq', 'short_id', [
			'unique' => true
		]);

    $this->insert('faq',[
      'short_id' => '1000',
      'question' => 'what is todevise',
      'answer' => 'Todevise is a new concept of online store where you can browse',
      'lang' => $en
    ]);

    $this->insert('faq',[
      'short_id' => '2000',
      'question' => 'what a deviser?',
      'answer' => 'a deviser is a someone ... test text',
      'lang' => $en
    ]);


  }

  public function down()
  {
    $this->dropAllIndexes('faq');
		$this->dropCollection('faq');
  }
}
