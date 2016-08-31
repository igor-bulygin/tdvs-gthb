<?php
namespace app\models;

use yii\base\Model;

class PersonMedia extends Model
{

    /**
     * @var string
     */
    public $header;

    /**
     * @var array
     */
    public $profile;

    /**
     * @var array
     */
    public $photos;

    public function rules()
    {
        return [
            [['header', 'profile'], 'required', 'on' => Person::SCENARIO_DEVISER_PROFILE_UPDATE],
        ];
    }

}