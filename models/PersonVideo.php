<?php
namespace app\models;

use yii\base\Model;

class PersonVideo extends Model
{

    /**
     * @var string
     */
    public $url;

    /**
     * @var array
     */
    public $products;

    public function rules()
    {
        return [
            [['url'], 'required', 'on' => Person::SCENARIO_DEVISER_VIDEOS_UPDATE],
            [['products'], 'safe', 'on' => Person::SCENARIO_DEVISER_VIDEOS_UPDATE],
        ];
    }

}