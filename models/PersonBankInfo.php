<?php
namespace app\models;

use app\helpers\CActiveRecord;

/**
 * @property string location
 * @property string bank_name
 * @property string institution_number
 * @property string transit_number
 * @property string account_number
 * @property string iban
 * @property string swift_bic
 * @property string account_type
 * @property string routing_number
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
			[['location', 'bank_name', 'institution_number', 'transit_number', 'account_number', 'iban', 'swift_bic', 'routing_number'], 'safe', 'on' => [Person::SCENARIO_DEVISER_CREATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_PROFILE]],
			['iban', 'app\validators\EIBANValidator'],
			['routing_number', 'app\validators\EABARoutingNumberValidator'],
			['swift_bic', 'app\validators\SwiftBicValidator'],
			['account_type', 'in', 'range' => [self::ACCOUNT_TYPE_CHECKING, self::ACCOUNT_TYPE_SAVINGS]],
		];
	}


	/**
	 * Add additional error to make easy show labels in client side
	 */
	public function afterValidate()
	{
		parent::afterValidate();
		foreach ($this->errors as $attribute => $error) {
			foreach ($error as $oneError) {
				$this->getSettings()->addError($attribute, $oneError);
			}
		};
		$this->clearErrors();
	}

}