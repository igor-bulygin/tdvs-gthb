<?php
namespace app\models;

use yii\base\Model;

class PersonPreferences extends Model
{

    /**
     * @var string $lang
     */
    public $lang;

    /**
     * @var string $currency
     */
    public $currency;


    public function rules()
    {
        return [
            [['lang', 'currency'], 'required', 'on' => Person::SCENARIO_DEVISER_UPDATE_PROFILE],
        ];
    }

}