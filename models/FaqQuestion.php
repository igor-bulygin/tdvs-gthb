<?php
namespace app\models;

use app\helpers\Utils;
use app\validators\TranslatableValidator;
use yii\base\Model;

/**
 * @property array $question
 * @property array $answer
 */
class FaqQuestion extends Model
{

    /**
     * @var array
     */
    public $question;

    /**
     * @var array
     */
    public $answer;


    public function rules()
    {
        return [
//            [['question', 'answer'], 'validateTranslatableField', 'on' => [Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT, Person::SCENARIO_DEVISER_UPDATE_DRAFT]],
//            [['question', 'answer'], 'safe', 'on' => [Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT, Person::SCENARIO_DEVISER_UPDATE_DRAFT]],
            [['question', 'answer'], 'required', 'on' => [Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT, Person::SCENARIO_DEVISER_UPDATE_DRAFT]],
            [['question', 'answer'], 'app\validators\TranslatableValidator', 'on' => [Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT, Person::SCENARIO_DEVISER_UPDATE_DRAFT]],
        ];
    }

	/**
	 * Custom validator for translatable fields
	 *
	 * @param $attribute
	 * @param $params
	 */
//	public function validateTranslatableField($attribute, $params)
//	{
//		$translatableField = $this->{$attribute};
//
//		$translatableValidator = new TranslatableValidator();
//		if (!$translatableValidator->validate($translatableField, $error)) {
//			$this->addError($attribute, $error);
//		}
//	}

}