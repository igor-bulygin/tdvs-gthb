<?php
namespace app\models;

/**
 * @property string access_token
 * @property string livemode
 * @property string refresh_token
 * @property string scope
 * @property string stripe_publishable_key
 * @property string stripe_user_id
 * @property string token_type
 *
 * @method PersonSettings getParentObject()
 */
class PersonStripeInfo extends EmbedModel
{
	const ACCOUNT_TYPE_CHECKING = 'checking';
	const ACCOUNT_TYPE_SAVINGS= 'savings';

	public function attributes()
	{
//		access_token : "sk_test_ijjOmJLJMIqgCivppzSAtE1P"
//		livemode : false
//		refresh_token : "rt_A3AliX24pI9giiLupTedOJhZVAkp2WwTGk8DWZEFJpjAuSJR"
//		scope : "read_write"
//		stripe_publishable_key : "pk_test_5QWDDSGoN8qNraBQ39p6Z5DL"
//		stripe_user_id : "acct_19f5PrJt4mveficF"
//		token_type : "bearer"
		return [
			'access_token',
			'livemode',
			'refresh_token',
			'scope',
			'stripe_publishable_key',
			'stripe_user_id',
			'token_type',
		];
	}

	public function getParentAttribute()
	{
		return "stripe_info";
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
				]
			],
		];
	}

}