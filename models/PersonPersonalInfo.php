<?php
namespace app\models;

use yii\base\Model;

class PersonPersonalInfo extends Model
{

    /**
     * @var string $name
     */
    public $name;

    /**
     * @var array $surnames
     */
    public $surnames;

    /**
     * @var string $country
     */
    public $country;

    /**
     * @var string $city
     */
    public $city;


    public function rules()
    {
        return [
            [['name', 'surnames', 'country', 'city'], 'required', 'on' => Person::SCENARIO_DEVISER_PROFILE_UPDATE],
        ];
    }

}