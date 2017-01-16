<?php
namespace app\models;

use yii\base\Model;

class PersonBankInfo extends Model
{

	const ACCOUNT_TYPE_CHECKING = 'checking';
	const ACCOUNT_TYPE_SAVINGS= 'savings';

	/* @var string */
	public $location;

	/* @var string */
	public $bank_name;

	/* @var string */
	public $institution_number;

	/* @var string */
	public $transit_number;

	/* @var string */
	public $account_number;

	/* @var string */
	public $swift_bic;

	/* @var string */
	public $account_type;

	/* @var string */
	public $routing_number;

	/**
	 * @var PersonSettings
	 */
	protected $settings;


	public function getParentAttribute()
	{
		return "bank_info";
	}

	/**
	 * @return PersonSettings
	 */
	public function getSettings()
	{
		return $this->settings;
	}

	/**
	 * @param PersonSettings $settings
	 */
	public function setSettings($settings)
	{
		$this->settings = $settings;
	}

	public function beforeValidate()
	{
		$this->setScenario($this->getSettings()->getScenario());
		return parent::beforeValidate();
	}

	public function rules()
	{
		return [
			[['location', 'bank_name', 'institution_number', 'transit_number', 'account_number', 'swift_bic', 'routing_number'], 'safe', 'on' => [Person::SCENARIO_DEVISER_CREATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_PROFILE]],
			['account_type', 'in', 'range' => [self::ACCOUNT_TYPE_CHECKING, self::ACCOUNT_TYPE_SAVINGS]],
		];
	}

}