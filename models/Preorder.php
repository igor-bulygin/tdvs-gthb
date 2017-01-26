<?php
namespace app\models;


/**
 * @property int $type
 * @property \MongoDate $end
 * @property \MongoDate $ship
 */
class Preorder extends EmbedModel {

	const NO = 0;
	const YES = 1;

	public function attributes() {
		return [
			'type',
			'end',
			'ship',
		];
	}

	public function getParentAttribute()
	{
		return "preorder";
	}

	public function rules()
	{
		return [
			['type', 'required', 'on' => Product2::SCENARIO_PRODUCT_PUBLIC],
			['type', 'in', 'range' => [self::YES, self::NO], 'on' => [Product2::SCENARIO_PRODUCT_PUBLIC, Product2::SCENARIO_PRODUCT_DRAFT]],
			[['ship', 'end'], 'required', 'on' => Product2::SCENARIO_PRODUCT_PUBLIC, 'when' => function($model) {
				return $model->type == self::YES;
			}],
		];
	}
}
