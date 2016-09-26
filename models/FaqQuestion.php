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

	public function init()
	{
		parent::init();

		$this->setScenario(Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT);
	}

	public function getParentAttribute()
	{
		return "faq";
	}

    public function rules()
    {
        return [
            [['question', 'answer'], 'required', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
            [['question', 'answer'], 'app\validators\TranslatableValidator', 'on' => [Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT, Person::SCENARIO_DEVISER_UPDATE_DRAFT, Person::SCENARIO_DEVISER_CREATE_DRAFT]],
        ];
    }

}