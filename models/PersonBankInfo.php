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
			['institution_number', 'validateInstitutionNumber'],
			['transit_number', 'validateTransitNumber'],
			['account_number', 'validateAccountNumber'],
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

	/**
	 * Validates an institution number
	 *
	 * @param $attribute
	 * @param $params
	 * @return bool TRUE if valid
	 */
	public function validateInstitutionNumber($attribute, $params)
	{
		$regExp = "/^[0-9]{3}$/";

		$value = $this->$attribute;
		preg_match($regExp, $value, $matches);

		return !empty($matches);
	}

	/**
	 * Validates a transit number
	 *
	 * @param $attribute
	 * @param $params
	 * @return bool TRUE if valid
	 */
	public function validateTransitNumber($attribute, $params)
	{
		$regExp = "/^[0-9]{5}$/";

		$value = $this->$attribute;
		preg_match($regExp, $value, $matches);

		return !empty($matches);
	}

	/**
	 * Validates an account number
	 *
	 * @param $attribute
	 * @param $params
	 * @return bool TRUE if valid
	 */
	public function validateAccountNumber($attribute, $params)
	{

		$value = $this->$attribute;
		if ($this->location == 'CN') {

			$regExp = "/^[0-9]{12}$/"; // Canada

		} elseif ($this->location == 'NZ') {

			$regExp = "/^[0-9]{15}[0-9]+$/"; // New Zeland

		} elseif ($this->location == 'US') {
			
			$regExp = "/^[0-9]{6}[0-9]*$/"; // USA

		} else {
			return true;
		}
		preg_match($regExp, $value, $matches);

		return !empty($matches);
	}

}