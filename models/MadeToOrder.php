<?php
namespace app\models;

use yii\base\Model;

class MadeToOrder extends Model {
	const NONE = 0;
	const DAYS = 1;

	/**
	 * @var int
	 */
	public $type;

	/**
	 * @var int
	 */
	public $value;

	/** @var  Product2 */
	protected $product;


	public function getParentAttribute()
	{
		return "madetoorder";
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
				['type', 'in', 'range' => [self::NONE, self::DAYS], 'on' => [Product2::SCENARIO_PRODUCT_PUBLIC, Product2::SCENARIO_PRODUCT_DRAFT]],
				['value', 'required', 'when' => function($model) {
					return $model->type == self::DAYS;
				}],
				['value', 'integer', 'when' => function($model) {
					return $model->type == self::DAYS;
				}],
		];
	}
}
