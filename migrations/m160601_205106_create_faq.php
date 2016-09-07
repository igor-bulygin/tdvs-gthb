<?php

use app\models\Lang;
use app\models\Faq;
use yii\mongodb\Migration;

class m160601_205106_create_faq extends Migration
{
	public function up() {
		$en = array_keys(Lang::EN_US_DESC)[0];
		$this->createCollection('faq');
		$this->createIndex('faq', 'short_id', [
			'unique' => true
		]);

		$this->insert('faq',[
			'short_id' => '10000',
			'title' => [
				$en => 'What is todevise'
			],
			'faqs' => [
					[
						'question' => [
							$en => 'what is todevise',
						],
						'answer' => [
							$en => 'Todevise is a new concept of online store where you can browse'
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

		$this->insert('faq',[
			'short_id' => '20000',
			'title' => [
				$en => 'Getting started as a member'
			],
			'faqs' => [
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
		$this->dropAllIndexes('faq');
		$this->dropCollection('faq');
	}
}
