<?php
namespace app\models;

/**
 * @property string $product_id
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
		];
	}

	public function rules()
	{
		return [
				[$this->attributes(), 'safe']
//				[['question', 'answer'], 'app\validators\TranslatableValidator', 'on' => [Person::SCENARIO_DEVISER_CREATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_PUBLISH_PROFILE, Person::SCENARIO_DEVISER_UPDATE_PROFILE, Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC ]],
		];
	}

}