<?php
namespace app\models;


/**
 * @property int $type
 * @property \MongoDate $end
 * @property \MongoDate $ship
 *
 * @method Product getParentObject()
 */
class Preorder extends EmbedModel {

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
			['type', 'required', 'on' => Product::SCENARIO_PRODUCT_PUBLIC],
			['type', 'in', 'range' => [self::YES, self::NO], 'on' => [Product::SCENARIO_PRODUCT_PUBLIC, Product::SCENARIO_PRODUCT_DRAFT]],
			[
				['ship', 'end'],
				'required',
				'on' => Product::SCENARIO_PRODUCT_PUBLIC,
				'when' => function($model) {
					return $model->type == self::YES;
				}
			],
			/*
			[
				['ship', 'end'],
				'yii\mongodb\validators\MongoDateValidator',
				'format' => 'Y-m-d\TH:i:s.u\Z',
			],
			*/
		];
	}
}
