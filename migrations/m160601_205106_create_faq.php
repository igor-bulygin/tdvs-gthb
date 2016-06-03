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
      'question' => [
        $en => 'what is todevise',
      ],
      'answer' => [
        $en => 'Todevise is a new concept of online store where you can browse'
      ]
    ]);

    $this->insert('faq',[
      'short_id' => '2000',
      'question' => [
        $en => 'what a deviser?',
      ],
      'answer' => [
        $en => 'a deviser is a someone ... test text'
      ]
    ]);

  }

  public function down()
  {
    $this->dropAllIndexes('faq');
		$this->dropCollection('faq');
  }
}
