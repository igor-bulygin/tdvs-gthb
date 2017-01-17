<?php
namespace app\models;

use app\helpers\CActiveRecord;

/**
 * @propery string $bank_info
 * @property PersonBankInfo $bankInfoMapping
 */
class PersonSettings extends CActiveRecord
{
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
			foreach ($error as $oneError) {
				$this->getPerson()->addError($attribute, $oneError);
			}
		};
		$this->clearErrors();
	}

}