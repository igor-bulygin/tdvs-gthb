<?php
namespace app\models;

/**
 * @property string weight_measure
 * @property string lang
 * @property PersonBankInfo $bankInfoMapping
 * @property PersonStripeInfo $stripeInfoMapping
 *
 * @method Person getParentObject()
 */
class PersonSettings extends EmbedModel
{
	// TODO deprecate
	public $currency;

	public function attributes()
	{
		return [
			'weight_measure',
			'lang',
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
				$this->attributes(),
				'safe',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
					Person::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					Person::SCENARIO_INFLUENCER_UPDATE_PROFILE,
					Person::SCENARIO_CLIENT_UPDATE,
				]
			],
			[
				'weight_measure',
				'in',
				'range' => MetricType::UNITS[MetricType::WEIGHT],
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			[
				'bankInfoMapping',
				'app\validators\EmbedDocValidator',
				'on' => [Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_PROFILE]
			],
			[
				'stripeInfoMapping',
				'app\validators\EmbedDocValidator',
				'on' => [Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_PROFILE]
			],
		];
	}

}