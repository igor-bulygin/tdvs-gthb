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

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $serializeFields = [];

	/**
	 * The attributes that should be serialized
	 *
	 * @var array
	 */
	protected static $retrieveExtraFields = [];

	// TODO deprecate
	public $currency;
	// TODO deprecate
	public $bank_info;

	public function attributes()
	{
		return [
			'weight_measure',
			'lang',
//			'bank_info',
			'stripe_info',
		];
	}

	public function getParentAttribute()
	{
		return "settings";
	}


	public function beforeValidate()
	{
		//$this->bankInfoMapping->setParentObject($this);
		$this->stripeInfoMapping->setParentObject($this);

		return parent::beforeValidate();
	}

//	public function embedBankInfoMapping()
//	{
//		return $this->mapEmbedded('bank_info', PersonBankInfo::className(), array('unsetSource' => false));
//	}

	public function embedStripeInfoMapping()
	{
		return $this->mapEmbedded('stripe_info', PersonStripeInfo::className(), array('unsetSource' => false));
	}


	public function rules()
	{
		return [
			[
				[
					'weight_measure',
					'lang',
					// 'bank_info',
					// 'stripe_info',
				],
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
				'app\validators\WeightUnitValidator',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
				]
			],
			/*
			[
				'bankInfoMapping',
				'app\validators\EmbedDocValidator',
				'on' => [Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_PROFILE]
			],
			*/
			[
				'stripeInfoMapping',
				'app\validators\EmbedDocValidator',
				'on' => [Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_PROFILE]
			],
		];
	}

}