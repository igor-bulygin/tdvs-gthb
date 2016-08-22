<?php
namespace app\models;

use yii\base\Model;

class PersonPersonalInfo extends Model
{

    /**
     * @var string
     */
    public $name;

    /**
     * @var array
     */
    public $surnames;

    /**
     * @var string
     */
    public $brand_name;

    /**
     * @var string
     */
    public $country;

    /**
     * @var string
     */
    public $city;


    public function rules()
    {
        return [
            [['name', 'surnames', 'brand_name', 'country', 'city'], 'required', 'on' => Person::SCENARIO_DEVISER_PROFILE_UPDATE],
        ];
    }

}