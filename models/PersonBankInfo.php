<?php
namespace app\models;

use app\helpers\CActiveRecord;

/**
 * @propery string $location
 * @propery string $bank_name
 * @propery string $institution_number
 * @propery string $transit_number
 * @propery string $account_number
 * @propery string $iban
 * @propery string $swift_bic
 * @propery string $account_type
 * @propery string $routing_number
 */
class PersonBankInfo extends CActiveRecord
{
	const ACCOUNT_TYPE_CHECKING = 'checking';
	const ACCOUNT_TYPE_SAVINGS= 'savings';

	/**
	 * @var PersonSettings
	 */
	protected $settings;

	public function attributes()
	{
		return [
			'location',
			'bank_name',
			'institution_number',
			'transit_number',
			'account_number',
			'iban',
			'swift_bic',
			'account_type',
			'routing_number',
		];
	}


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