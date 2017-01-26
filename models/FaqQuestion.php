<?php
namespace app\models;

use yii\base\Model;

/**
 * @property array $question
 * @property array $answer
 */
class FaqQuestion extends EmbedModel
{

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
            [['question', 'answer'], 'required', 'on' => [Person::SCENARIO_DEVISER_UPDATE_PROFILE, Product2::SCENARIO_PRODUCT_PUBLIC]],
            [['question', 'answer'], 'app\validators\TranslatableValidator', 'on' => [Person::SCENARIO_DEVISER_CREATE_DRAFT, Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_PUBLISH_PROFILE, Person::SCENARIO_DEVISER_UPDATE_PROFILE, Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC ]],
        ];
    }

}