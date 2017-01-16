<?php
namespace app\models;

use app\helpers\CActiveRecord;

/**
 * @property PersonBankInfo $bankInfoMapping
 */
class PersonSettings extends CActiveRecord
{
	public $bank_info;

	/** @var  Person */
	protected $person;

	public function attributes()
	{
		return [
				'bank_info',
		];
	}

	public function getParentAttribute()
	{
		return "settings";
	}

	/**
	 * @return Person
	 */
	public function getPerson()
	{
		return $this->person;
	}

	/**
	 * @param Person $person
	 */
	public function setPerson($person)
	{
		$this->person = $person;
	}

	public function beforeValidate()
	{
		$this->setScenario($this->getPerson()->getScenario());

		$this->bankInfoMapping->setSettings($this);

		return parent::beforeValidate();
	}

	public function embedBankInfoMapping()
	{
		return $this->mapEmbedded('bank_info', PersonBankInfo::className(), array('unsetSource' => false));
	}


	public function rules()
	{
		return [
				['bank_info', 'safe', 'on' => [Person::SCENARIO_DEVISER_CREATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_PROFILE]],
				['bankInfoMapping', 'app\validators\EmbedDocValidator'], // to apply rules
		];
	}


	/**
	 * Add additional error to make easy show labels in client side
	 */
	public function afterValidate()
	{
		parent::afterValidate();
		foreach ($this->errors as $attribute => $error) {
			switch ($attribute) {
				default:
					//TODO: Fix this! Find other way to determine if was a "required" field
					if (strpos($error[0], 'cannot be blank') !== false || strpos($error[0], 'no puede estar vacío') !== false) {
						$this->getPerson()->addError("required", $attribute);
					}
					break;
			}
		};
	}

}