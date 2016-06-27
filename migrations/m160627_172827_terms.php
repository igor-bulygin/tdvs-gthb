<?php
use app\models\Lang;
use app\models\Faq;
use yii\mongodb\Migration;

class m160627_172827_terms extends Migration
{
    public function up()
    {
		$en = array_keys(Lang::EN_US)[0];
		$this->createCollection('term');
		$this->createIndex('term', 'short_id', [
			'unique' => true
		]);

		$this->insert('term',[
			'short_id' => '10000',
			'title' => [
				$en => 'Term What is todevise'
			],
			'terms' => [
					[
						'question' => [
							$en => 'term what is todevise',
						],
						'answer' => [
							$en => 'term Todevise is a new concept of online store where you can browse'
						]
					],
					[
						'question' => [
							$en => 'what is a deviser',
						],
						'answer' => [
							$en => 'A deviser description text'
						]
					]
			]
		]);

		$this->insert('tern',[
			'short_id' => '20000',
			'title' => [
				$en => 'Getting started as a member'
			],
			'terms' => [
					[
						'question' => [
							$en => 'be a member?',
						],
						'answer' => [
							$en => 'how to be a member'
						]
					],
					[
						'question' => [
							$en => 'what to do?',
						],
						'answer' => [
							$en => 'To do something'
						]
					]
			]
		]);

    }

    public function down()
    {
		$this->dropAllIndexes('term');
		$this->dropCollection('term');
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
