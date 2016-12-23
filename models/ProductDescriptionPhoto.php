<?php
namespace app\models;

use yii\base\Model;

/**
 * @property string|mixed name
 * @property string|mixed title
 * @property string|mixed description
 */
class ProductDescriptionPhoto extends Model
{
	/**
	 * @var string
	 */
	public $name;

	/**
	 * @var array
	 */
	public $title;

	/**
	 * @var array
	 */
	public $description;

	/**
	 * The attributes that should be translated
	 *
	 * @var array
	 */
	public static $translatedAttributes = ['title', 'description'];

	public function getParentAttribute()
	{
		return "description_photos";
	}

	/**
	 * @return ProductMedia
	 */
	public function getMedia()
	{
		return $this->media;
	}

	/**
	 * @param ProductMedia $media
	 */
	public function setMedia($media)
	{
		$this->media = $media;
	}

	public function beforeValidate()
	{
		$this->setScenario($this->getMedia()->getScenario());
		return parent::beforeValidate();
	}

	public function rules()
    {
        return [
	        [['name'], 'safe', 'on' => [Product2::SCENARIO_PRODUCT_DRAFT, Product2::SCENARIO_PRODUCT_PUBLIC]],
            [['name'], 'required', 'on' => Product2::SCENARIO_PRODUCT_PUBLIC],
			[['title', 'description'], 'app\validators\TranslatableRequiredValidator'],
        ];
    }

}