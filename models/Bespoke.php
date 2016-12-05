<?php
namespace app\models;

use yii\base\Model;

class Bespoke extends Model
{
	const NO = 0;
	const YES = 1;

	/**
	 * @var int
	 */
	public $type;

	/**
	 * @var array
	 */
	public $value;

	/** @var  Product2 */
	protected $product;


	public function getParentAttribute()
	{
		return "bespoke";
	}


	/**
	 * @return Product2
	 */
	public function getProduct()
	{
		return $this->product;
	}

	/**
	 * @param Product2 $product
	 */
	public function setProduct($product)
	{
		$this->product = $product;
	}


	public function beforeValidate()
	{
		$this->setScenario($this->getProduct()->getScenario());

		return parent::beforeValidate();
	}

	public function rules()
	{
		return [
				['type', 'required', 'on' => Product2::SCENARIO_PRODUCT_PUBLIC],
				['type', 'in', 'range' => [self::YES, self::NO], 'on' => [Product2::SCENARIO_PRODUCT_PUBLIC, Product2::SCENARIO_PRODUCT_DRAFT]],
				['value', 'required', 'when' => function($model) {
					return $model->type == self::YES;
				}],
				[
					'value',
					'app\validators\TranslatableValidator',
					'on' => [Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC],
				],
		];
	}
}
