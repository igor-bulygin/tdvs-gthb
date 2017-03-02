<?php
namespace app\models;

/**
 * @property PersonBankInfo $bankInfoMapping
 * @property PersonStripeInfo $stripeInfoMapping
 *
 * @method Person getParentObject()
 */
class PersonSettings extends EmbedModel
{

	public function attributes()
	{
		return [
			'bank_info',
			'stripe_info',
		];
	}

	public function getParentAttribute()
	{
		return "settings";
	}


	public function beforeValidate()
	{
		$this->bankInfoMapping->setParentObject($this);
		$this->stripeInfoMapping->setParentObject($this);

		return parent::beforeValidate();
	}

	public function embedBankInfoMapping()
	{
		return $this->mapEmbedded('bank_info', PersonBankInfo::className(), array('unsetSource' => false));
	}

	public function embedStripeInfoMapping()
	{
		return $this->mapEmbedded('stripe_info', PersonStripeInfo::className(), array('unsetSource' => false));
	}


	public function rules()
	{
		return [
			[
				$this-$this->attributes(),
				'safe',
				'on' => [
					Person::SCENARIO_DEVISER_CREATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
					Person::SCENARIO_INFLUENCER_CREATE_DRAFT,
				]
			],
			['bankInfoMapping', 'app\validators\EmbedDocValidator'], // to apply rules
			['stripeInfoMapping', 'app\validators\EmbedDocValidator'], // to apply rules
		];
	}

}