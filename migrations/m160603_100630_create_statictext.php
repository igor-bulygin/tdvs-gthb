<?php

use app\models\Lang;
use yii\mongodb\Migration;
use app\models\StaticText;

// return [
//   '_id',
//   'short_id',
//   'title',
//   'text',
//   'lang',
//   'static_section'
// ];



class m160603_100630_create_statictext extends Migration
{
	public function up(){
		$en = array_keys(Lang::EN_US)[0];
		$this->createCollection('statictext');
		$this->createIndex('statictext', 'short_id', [
			'unique' => true
		]);

		$this->insert('statictext',[
			'short_id' => '1000',
			'title' => [
				$en => 'general information',
			],
			'text' => [
				$en => 'Below are the General Conditions of Use (hereinafter GENERAL CONDITIONS) which govern the relationship between the user of the services offered by this website'
			],
			'static_section' => 'terms'
		]);

		$this->insert('statictext',[
			'short_id' => '2000',
			'title' => [
				$en => 'company identification',
			],
			'text' => [
				$en => 'The owner and manager of the WEBSITES is the company, TESSARACTIC, S.L., with VAT Nº B55136360, and registered in the Trade Register of Girona, under Spanish law.'
			],
			'static_section' => 'terms'
		]);
	}

	public function down()
	{
		$this->dropAllIndexes('statictext');
		$this->dropCollection('statictext');
	}
}
