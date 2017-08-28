<?php
namespace app\models;

/**
 * @property int $type
 * @property int $value
 *
 * @method Product getParentObject()
 */
class Bespoke extends EmbedModel
{
	const NO = 0;
	const YES = 1;

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $serializeFields = [];

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $retrieveExtraFields = [];


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
				['type', 'required', 'on' => Product::SCENARIO_PRODUCT_PUBLIC],
				['type', 'in', 'range' => [self::YES, self::NO], 'on' => [Product::SCENARIO_PRODUCT_PUBLIC, Product::SCENARIO_PRODUCT_DRAFT]],
				[
					'value',
					'app\validators\TranslatableValidator',
					'on' => [Product::SCENARIO_PRODUCT_DRAFT, Product::SCENARIO_PRODUCT_PUBLIC],
				],
		];
	}
}
