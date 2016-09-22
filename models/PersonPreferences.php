<?php
namespace app\models;

use yii\base\Model;

/**
 * @property string lang
 * @property string currency
 */
class PersonPreferences extends Model
{

    /**
     * @var string $lang
     */
    public $lang = Lang::EN_US;

    /**
     * @var string $currency
     */
    public $currency;

	public function init()
	{
		parent::init();

		$this->setScenario(Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT);
	}


	public function rules()
    {
        return [
            [['lang', 'currency'], 'required', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
	        [['lang', 'currency'], 'safe', 'on' => [Person::SERIALIZE_SCENARIO_LOAD_SUB_DOCUMENT, Person::SCENARIO_DEVISER_UPDATE_DRAFT]],
        ];
    }

}