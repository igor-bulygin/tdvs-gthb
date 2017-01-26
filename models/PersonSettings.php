<?php
namespace app\models;

/**
 * @property PersonBankInfo $bankInfoMapping
 *
 * @method Person getParentObject()
 */
class PersonSettings extends EmbedModel
{

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


	public function beforeValidate()
	{
		$this->bankInfoMapping->setParentObject($this);

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

}