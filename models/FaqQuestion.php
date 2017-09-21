<?php
namespace app\models;

use yii\base\Model;

/**
 * @property array $question
 * @property array $answer
 *
 * @method Person|Product getParentObject()
 */
class FaqQuestion extends EmbedModel
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


	public function attributes() {
		return [
			'question',
			'answer',
		];
	}

	public function getParentAttribute()
	{
		return "faq";
	}


	/**
	 * @param Model $model
	 */
	public function setModel($model)
	{
		$this->model = $model;
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
					Product::SCENARIO_PRODUCT_DRAFT,
					Product::SCENARIO_PRODUCT_PUBLIC,
				]
			],
			[
				['question', 'answer'],
				'required',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
					Person::SCENARIO_INFLUENCER_UPDATE_PROFILE,
					Product::SCENARIO_PRODUCT_PUBLIC,
				]
			],
			[
				['question', 'answer'],
				'app\validators\TranslatableRequiredValidator',
				'on' => [
					Person::SCENARIO_DEVISER_UPDATE_DRAFT,
					Person::SCENARIO_DEVISER_UPDATE_PROFILE,
					Person::SCENARIO_INFLUENCER_UPDATE_DRAFT,
					Person::SCENARIO_INFLUENCER_UPDATE_PROFILE,
					Product::SCENARIO_PRODUCT_DRAFT,
					Product::SCENARIO_PRODUCT_PUBLIC,
				]
			],
		];
	}

}