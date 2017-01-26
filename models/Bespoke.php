<?php
namespace app\models;

/**
 * @property int $type
 * @property int $value
 *
 * @method Product2 getParentObject()
 */
class Bespoke extends EmbedModel
{
	const NO = 0;
	const YES = 1;

	public function attributes() {
		return [
			'type',
			'value',
		];
	}

	public function getParentAttribute()
	{
		return "bespoke";
	}

	public function rules()
	{
		return [
				['type', 'required', 'on' => Product2::SCENARIO_PRODUCT_PUBLIC],
				['type', 'in', 'range' => [self::YES, self::NO], 'on' => [Product2::SCENARIO_PRODUCT_PUBLIC, Product2::SCENARIO_PRODUCT_DRAFT]],
				[
					'value',
					'app\validators\TranslatableValidator',
					'on' => [Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC],
				],
		];
	}
}
