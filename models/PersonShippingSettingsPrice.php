<?php
namespace app\models;

/**
 * @propert double min_weight
 * @propert double max_weight
 * @propert double price
 * @propert double price_express
 *
 * @method PersonShippingSettings getParentObject()
 */
class PersonShippingSettingsPrice extends EmbedModel
{

	public function attributes()
	{
		return [
			'min_weight',
			'max_weight',
			'price',
			'price_express',
		];
	}

	public function getParentAttribute()
	{
		return "prices";
	}

	public function rules()
	{
		return [
			[
				$this->attributes(),
				'safe',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			[['min_weight', 'price'], 'required'],
			[
				'min_weight', 'number', 'min' => 0,
			],
			[
				'price', 'number', 'min' => 0,
			],
		];
	}
}