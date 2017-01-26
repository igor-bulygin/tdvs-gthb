<?php
namespace app\models;

/**
 * @property int $type
 * @property int $value
 */
class MadeToOrder extends EmbedModel {
	const NONE = 0;
	const DAYS = 1;

	public function attributes() {
		return [
			'type',
			'value',
		];
	}

	public function getParentAttribute()
	{
		return "madetoorder";
	}

	public function rules()
	{
		return [
				['type', 'required', 'on' => Product2::SCENARIO_PRODUCT_PUBLIC],
				['type', 'in', 'range' => [self::NONE, self::DAYS], 'on' => [Product2::SCENARIO_PRODUCT_PUBLIC, Product2::SCENARIO_PRODUCT_DRAFT]],
				['value', 'required', 'on' => Product2::SCENARIO_PRODUCT_PUBLIC, 'when' => function($model) {
					return $model->type == self::DAYS;
				}],
				['value', 'integer', 'when' => function($model) {
					return $model->type == self::DAYS;
				}],
		];
	}
}
