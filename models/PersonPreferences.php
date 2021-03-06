<?php
namespace app\models;

/**
 * @property string lang
 * @property string currency
 *
 * @method Person getParentObject()
 */
class PersonPreferences extends EmbedModel
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

	public function attributes()
	{
		return [
			'lang',
			'currency',
		];
	}

	public function getParentAttribute()
	{
		return "preferences";
	}

	public function init()
	{
		parent::init();

		$this->lang = Lang::EN_US;
	}


	public function rules()
	{
		return [
			[
				$this->attributes(),
				'safe',
				'on' => [
					Person::SCENARIO_DEVISER_CREATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
					Person::SCENARIO_INFLUENCER_CREATE_DRAFT,
					Person::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					Person::SCENARIO_INFLUENCER_UPDATE_PROFILE,
					Person::SCENARIO_CLIENT_UPDATE,
				]
			],
			[['lang', 'currency'], 'required', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
		];
	}

}