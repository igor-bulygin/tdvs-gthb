<?php
namespace app\models;

use app\helpers\CActiveRecord;
use Yii;
use yii\base\Model;

/**
 * @property string $product_id
 * @property string $price_stock_id
 * @property int $quantity
 * @property string $deviser_id
 * @property double $price
 * @property double $weight
 * @property array $options
 */
class OrderProduct extends CActiveRecord
{
	/** @var  Model */
	protected $model;

	public function getParentAttribute()
	{
		return "products";
	}

	public function attributes() {
		return [
				'product_id',
				'price_stock_id',
				'quantity',
				'deviser_id',
				'price',
				'weight',
				'options',
		];
	}

	/**
	 * @return Model
	 */
	public function getModel()
	{
		return $this->model;
	}

	/**
	 * @param Model $model
	 */
	public function setModel($model)
	{
		$this->model = $model;
	}

	public function beforeValidate()
	{
		$this->setScenario($this->getModel()->getScenario());

		return parent::beforeValidate();
	}

	public function rules()
	{
		return [
				[$this->attributes(), 'safe']
//				[['question', 'answer'], 'app\validators\TranslatableValidator', 'on' => [Person::SCENARIO_DEVISER_CREATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_PUBLISH_PROFILE, Person::SCENARIO_DEVISER_UPDATE_PROFILE, Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC ]],
		];
	}

}