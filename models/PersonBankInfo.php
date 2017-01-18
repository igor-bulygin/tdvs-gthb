<?php
namespace app\models;

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
 * @property string bsb_code
 */
class PersonBankInfo extends EmbedModel
{
	const ACCOUNT_TYPE_CHECKING = 'checking';
	const ACCOUNT_TYPE_SAVINGS= 'savings';

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
			'bsb_code',
		];
	}


	public function getParentAttribute()
	{
		return "bank_info";
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
			['bsb_code', 'validateBsbCode'],
			['account_type', 'in', 'range' => [self::ACCOUNT_TYPE_CHECKING, self::ACCOUNT_TYPE_SAVINGS]],
		];
	}

	/**
	 * Validates an institution number
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateInstitutionNumber($attribute, $params)
	{
		$regExp = "/^[0-9]{3}$/";

		$value = $this->$attribute;
		preg_match($regExp, $value, $matches);

		if (empty($matches)) {
			$this->addError($attribute, 'The institution number must contain 3 digits');
		}
	}

	/**
	 * Validates a transit number
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateTransitNumber($attribute, $params)
	{
		$regExp = "/^[0-9]{5}$/";

		$value = $this->$attribute;
		preg_match($regExp, $value, $matches);

		if (empty($matches)) {
			$this->addError($attribute, 'The transit number must contain 5 digits');
		}
	}

	/**
	 * Validates an account number
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateAccountNumber($attribute, $params)
	{

		$value = $this->$attribute;
		if ($this->location == 'CA') {
			$regExp = "/^[0-9]{12}$/"; // Canada
			$message = 'The account number must contain 12 digits for Canada';
		} elseif ($this->location == 'NZ') {
			$regExp = "/^[0-9]{15}([0-9]{1})?$/"; // New Zeland
			$message = 'The account number must contain 15-16 digits for New Zeland';
		} elseif ($this->location == 'US') {
			$regExp = "/^[0-9]{6}[0-9]*$/"; // USA
			$message = 'The account number must contain 6 or more digits for US';
		} elseif ($this->location == 'AU') {
			$regExp = "/^[A-Z0-9]{6,10}*$/"; // Australia
			$message = 'The account number must contain 6 to 10 characteres for Australia';
		}

		if (empty($regExp)) {
			return; // nothing to do
		}

		preg_match($regExp, $value, $matches);

		if (empty($matches)) {
			$this->addError($attribute, $message);
		}
	}

	/**
	 * Validates an account number
	 *
	 * @param $attribute
	 * @param $params
	 */
	public function validateBsbCode($attribute, $params)
	{

		$value = $this->$attribute;
		$regExp = "/^[0-9]{6}$/";

		preg_match($regExp, $value, $matches);

		if (empty($matches)) {
			$this->addError($attribute, 'The BSB code must contain 6 digits');
		}
	}

}