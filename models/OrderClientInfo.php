<?php
namespace app\models;

use Yii;
use yii\base\Model;

/**
 * @property string $first_name
 * @property string $last_name
 */
class OrderClientInfo extends Model
{
	/** @var  Model */
	protected $model;

	public function getParentAttribute()
	{
		return "order";
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
//				[['question', 'answer'], 'required', 'on' => [Person::SCENARIO_DEVISER_UPDATE_PROFILE, Product2::SCENARIO_PRODUCT_PUBLIC]],
//				[['question', 'answer'], 'app\validators\TranslatableValidator', 'on' => [Person::SCENARIO_DEVISER_CREATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_PUBLISH_PROFILE, Person::SCENARIO_DEVISER_UPDATE_PROFILE, Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC ]],
		];
	}

}