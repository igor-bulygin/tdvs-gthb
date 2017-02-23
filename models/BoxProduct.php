<?php
namespace app\models;

use MongoDate;

/**
 * @property string product_id
 * @property MongoDate created_at
 * @property MongoDate updated_at
 *
 * @method Box getParentObject()
 */
class BoxProduct extends EmbedModel
{
	public function getParentAttribute()
	{
		return "products";
	}

	public function attributes() {
		return [
				'product_id',
				'created_at',
				'updated_at',
		];
	}

	public function beforeSave($insert)
	{
		if (empty($this->created_at)) {
			$this->created_at = new MongoDate();
		}
		$this->updated_at = new MongoDate();

		return parent::beforeSave($insert);
	}

	public function rules()
	{
		return [
				[$this->attributes(), 'safe']
//				[['question', 'answer'], 'app\validators\TranslatableValidator', 'on' => [Person::SCENARIO_DEVISER_CREATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_PUBLISH_PROFILE, Person::SCENARIO_DEVISER_UPDATE_PROFILE, Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC ]],
		];
	}

}