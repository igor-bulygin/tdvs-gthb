<?php
namespace app\models;

use yii\base\Model;

class Preorder extends Model {

	const NO = 0;
	const YES = 1;

	/**
	 * @var int
	 */
	public $type;

	/**
	 * @var \MongoDate
	 */
	public $end;

	/**
	 * @var \MongoDate
	 */
	public $ship;

	/** @var  Product2 */
	protected $product;

	public function getParentAttribute()
	{
		return "preorder";
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
			[['ship', 'end'], 'required', 'on' => Product2::SCENARIO_PRODUCT_PUBLIC, 'when' => function($model) {
				return $model->type == self::YES;
			}],
		];
	}
}
