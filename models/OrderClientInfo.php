<?php
namespace app\models;

use app\helpers\CActiveRecord;
use Yii;
use yii\base\Model;

/**
 * @property string $first_name
 * @property string $last_name
 * @property string $email
 * @property array $phone1
 * @property array $phone2
 * @property string $country
 * @property string $city
 * @property string $address
 * @property string $zipcode
 */
class OrderClientInfo extends CActiveRecord
{
	/** @var  Model */
	protected $model;

	public function getParentAttribute()
	{
		return "client_info";
	}

	public function attributes() {
		return [
			'first_name',
			'last_name',
			'email',
			'phone1',
			'phone2',
			'country',
			'city',
			'address',
			'zipcode',
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
		];
	}

}